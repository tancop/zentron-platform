const subtotalEl = document.getElementById('subtotal-price');
const deliveryEl = document.getElementById('delivery-price');
const totalEl = document.getElementById('total-price');

function toNumber(s: string|null|undefined): number {
  if (!s) return 0;
  const cleaned = s.replace(/[^0-9.,-]+/g, '').replace(',', '.');
  const n = parseFloat(cleaned);
  return Number.isFinite(n) ? n:0;
}

function fmt(n: number): string {
  return (Math.round(n * 100)/100).toFixed(2) + ' €';
}

document.addEventListener('DOMContentLoaded', () => {
  if (!subtotalEl||!deliveryEl||!totalEl) return;

  const subtotalNode = subtotalEl;
  const deliveryNode = deliveryEl;
  const totalNode = totalEl;

  const radios = document.querySelectorAll<HTMLInputElement>('input[name="delivery-method"]');
  if ((!radios)||(radios.length === 0)) return;

  function getDelivery(r: HTMLInputElement): number {
    const dp = r.dataset.price;
    if (dp) return toNumber(dp);
    const label = r.closest('label');
    const txt = label ? (label.querySelector('span:last-child')?.textContent ?? '') : '';
    return toNumber(txt);
  }

  function update() {
    const checked = Array.from(radios).find(r => r.checked);
    const subtotal = toNumber(subtotalNode.textContent || '0');
    const delivery = subtotal > 0 && checked ? getDelivery(checked) : 0;
    deliveryNode.textContent = fmt(delivery);
    totalNode.textContent = fmt(subtotal + delivery);
  }

  radios.forEach(r => r.addEventListener('change', update));
  update();
});
