'use strict';
/* Products and categories now live in src/data/*.json (loaded by load-data.js).
   Editorial copy that isn't "database data" just stays here as plain arrays. */
let PRODUCTS = [];
let CATEGORIES = [];

const WHY = [
  { icon: '🌿', title: 'Natural Ingredients', text: 'Fermented botanicals, centella, ginseng — sourced from Korean farms.' },
  { icon: '🩺', title: 'Dermatologist Tested', text: 'Every formula clinically tested for sensitive skin compatibility.' },
  { icon: '🐰', title: 'Cruelty Free', text: 'Never tested on animals, certified by Leaping Bunny.' },
  { icon: '♻️', title: 'Sustainable Packaging', text: 'Recyclable glass and refill pouches on every core product.' },
  { icon: '⚡', title: 'Fast Absorption', text: 'Lightweight textures layer in seconds, no pilling, no residue.' },
  { icon: '🌸', title: 'Suitable for Sensitive Skin', text: 'Fragrance-optional, pH 5.5 formulas for reactive skin types.' },
];

const ROUTINE = [
  { icon: '🧼', title: 'Cleanse', text: 'Double cleanse with oil then foam to fully remove impurities.' },
  { icon: '💦', title: 'Tone', text: 'Rebalance pH and prep skin to drink in what comes next.' },
  { icon: '💉', title: 'Treat', text: 'Target concerns with serums and ampoules rich in actives.' },
  { icon: '🧴', title: 'Moisturize', text: 'Seal it all in and reinforce the barrier with cream or balm.' },
];

const REVIEWS = [
  { name: 'Ji-woo Park', role: 'Verified buyer', rating: 5, avatar: 'https://i.pravatar.cc/100?img=32', text: 'My skin has never looked this even. The Rice Glow Ampoule is a religion at this point.' },
  { name: 'Maya Chen', role: 'Verified buyer', rating: 5, avatar: 'https://i.pravatar.cc/100?img=47', text: 'Sensitive, rosacea-prone skin and this is the first routine that never stings. Repurchasing forever.' },
  { name: 'Sofia Torres', role: 'Verified buyer', rating: 4, avatar: 'https://i.pravatar.cc/100?img=15', text: 'The snail mucin essence sold me on Korean skincare. Texture and tone visibly better in three weeks.' },
  { name: 'Amara Obi', role: 'Verified buyer', rating: 5, avatar: 'https://i.pravatar.cc/100?img=57', text: 'Fast shipping, gorgeous packaging, and the sunscreen leaves zero white cast on deep skin tones.' },
];

const IG = [
  'photo-1522335789203-aabd1fc54bc9','photo-1596462502278-27bfdc403348','photo-1620916566398-39f1143ab7be',
  'photo-1570172619644-dfd03ed5d881','photo-1608248543803-ba4f8c70ae0b','photo-1612817288484-6f916006741a',
];

const FAQS = [
  { q: 'How long until I see results?', a: 'Most customers notice improved hydration within a week and visible texture/tone changes by 4–8 weeks of consistent use.' },
  { q: 'Are your products cruelty-free?', a: 'Yes — every Korean Point product is Leaping Bunny certified and never tested on animals at any stage.' },
  { q: 'What is your return policy?', a: 'Unopened products can be returned within 30 days. Opened products qualify for our satisfaction guarantee within 14 days.' },
  { q: 'Do you ship internationally?', a: 'We ship to over 40 countries. Orders over $60 ship free; typical delivery is 5–9 business days.' },
  { q: 'Is this safe for sensitive or acne-prone skin?', a: 'Most formulas are fragrance-optional and pH 5.5. We recommend patch-testing any new active for 48 hours first.' },
];
