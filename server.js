// Korean Point — tiny local server.
// No frameworks: plain Node http + fs. Serves the site and saves
// users/orders into JSON files instead of a database.

const http = require('http');
const fs = require('fs');
const path = require('path');
const url = require('url');

const ROOT = __dirname;
const USERS_FILE = path.join(ROOT, 'src/data/users.json');
const ORDERS_FILE = path.join(ROOT, 'src/data/orders.json');
const PORT = 3000;

function readJson(file) {
  return JSON.parse(fs.readFileSync(file, 'utf8'));
}
function writeJson(file, data) {
  fs.writeFileSync(file, JSON.stringify(data, null, 2));
}
function sendJson(res, status, data) {
  res.writeHead(status, { 'Content-Type': 'application/json' });
  res.end(JSON.stringify(data));
}
function readBody(req) {
  return new Promise((resolve, reject) => {
    let body = '';
    req.on('data', (chunk) => (body += chunk));
    req.on('end', () => {
      try { resolve(body ? JSON.parse(body) : {}); }
      catch { reject(new Error('Invalid JSON')); }
    });
  });
}

const MIME = {
  '.html': 'text/html', '.css': 'text/css', '.js': 'application/javascript',
  '.json': 'application/json', '.svg': 'image/svg+xml', '.png': 'image/png',
  '.ico': 'image/x-icon',
};

function serveStatic(req, res, pathname) {
  if (pathname === '/') {
    // redirect (not just serve) so the browser URL actually becomes /src/pages/...
    // otherwise relative css/js paths like "../css/x.css" resolve against "/" and 404
    res.writeHead(302, { Location: '/src/pages/index.html' });
    return res.end();
  }
  const filePath = path.join(ROOT, decodeURIComponent(pathname));

  // don't allow escaping the project folder
  if (!filePath.startsWith(ROOT)) { res.writeHead(403); return res.end('Forbidden'); }

  fs.readFile(filePath, (err, data) => {
    if (err) { res.writeHead(404); return res.end('Not found'); }
    const ext = path.extname(filePath);
    res.writeHead(200, { 'Content-Type': MIME[ext] || 'application/octet-stream' });
    res.end(data);
  });
}

async function handleRegister(req, res) {
  const body = await readBody(req);
  const name = (body.name || '').trim();
  const email = (body.email || '').trim().toLowerCase();
  const birthdate = body.birthdate || '';
  const password = body.password || '';

  const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  const today = new Date().toISOString().slice(0, 10);

  if (name.length < 2) return sendJson(res, 400, { error: 'Please enter your full name.' });
  if (!emailOk) return sendJson(res, 400, { error: 'Please enter a valid email address.' });
  if (!birthdate || birthdate > today) return sendJson(res, 400, { error: 'Birthdate cannot be in the future.' });
  if (password.length < 6) return sendJson(res, 400, { error: 'Password must be at least 6 characters.' });

  const users = readJson(USERS_FILE);
  if (users.some((u) => u.email === email)) {
    return sendJson(res, 400, { error: 'An account with this email already exists.' });
  }

  const user = {
    id: 'u' + Date.now(),
    name, email, birthdate,
    password,
    createdAt: new Date().toISOString(),
  };
  users.push(user);
  writeJson(USERS_FILE, users);

  sendJson(res, 200, { id: user.id, name: user.name, email: user.email });
}

async function handleLogin(req, res) {
  const body = await readBody(req);
  const email = (body.email || '').trim().toLowerCase();
  const password = body.password || '';

  const users = readJson(USERS_FILE);
  const user = users.find((u) => u.email === email && u.password === password);
  if (!user) return sendJson(res, 400, { error: 'Invalid email or password.' });

  sendJson(res, 200, { id: user.id, name: user.name, email: user.email });
}

async function handleCreateOrder(req, res) {
  const body = await readBody(req);
  if (!body.userId || !Array.isArray(body.items) || !body.items.length) {
    return sendJson(res, 400, { error: 'Order is missing items.' });
  }
  const orders = readJson(ORDERS_FILE);
  const order = {
    id: 'KP-' + Date.now(),
    userId: body.userId,
    items: body.items,
    total: body.total,
    name: body.name,
    email: body.email,
    address: body.address,
    city: body.city,
    zip: body.zip,
    createdAt: new Date().toISOString(),
  };
  orders.push(order);
  writeJson(ORDERS_FILE, orders);

  sendJson(res, 200, { id: order.id });
}

const server = http.createServer(async (req, res) => {
  const { pathname } = url.parse(req.url);

  try {
    if (req.method === 'POST' && pathname === '/api/register') return await handleRegister(req, res);
    if (req.method === 'POST' && pathname === '/api/login') return await handleLogin(req, res);
    if (req.method === 'POST' && pathname === '/api/orders') return await handleCreateOrder(req, res);
    if (req.method === 'GET') return serveStatic(req, res, pathname);
    res.writeHead(404); res.end('Not found');
  } catch (err) {
    sendJson(res, 500, { error: 'Server error.' });
  }
});

server.listen(PORT, () => console.log(`Korean Point running at http://localhost:${PORT}`));
