const amountField = document.getElementById("amount-field");
const minusBtn = document.getElementById("amount-minus");
const plusBtn = document.getElementById("amount-plus");

const MIN_AMOUNT = 1;
const MAX_AMOUNT = 999;

if (
  amountField instanceof HTMLInputElement &&
  minusBtn instanceof HTMLButtonElement &&
  plusBtn instanceof HTMLButtonElement
){
  amountField.setAttribute('min', String(MIN_AMOUNT));
  amountField.setAttribute('max', String(MAX_AMOUNT));

  const parse = (v: string) => Number.parseInt(v || String(MIN_AMOUNT));

  const syncButtons = () => {
    let amount = parse(amountField.value);
    if (Number.isNaN(amount) || amount < MIN_AMOUNT) amount = MIN_AMOUNT;
    if (amount > MAX_AMOUNT) amount = MAX_AMOUNT;
    amountField.value = String(amount);
    minusBtn.disabled = amount <= MIN_AMOUNT;
    plusBtn.disabled = amount >= MAX_AMOUNT;
  };

  plusBtn.addEventListener('click', () => {
    let amount = parse(amountField.value);
    if (Number.isNaN(amount)) amount = MIN_AMOUNT;
    if (amount < MAX_AMOUNT) amount += 1;
    amountField.value = String(amount);
    syncButtons();
  });

  minusBtn.addEventListener('click', () => {
    if (!minusBtn.disabled) {
      let amount = parse(amountField.value);
      if (Number.isNaN(amount)) amount = MIN_AMOUNT;
      if (amount>MIN_AMOUNT) amount -= 1;
      amountField.value = String(amount);
      syncButtons();
    }
  });

  amountField.addEventListener('input', syncButtons);
  amountField.addEventListener('keydown', (e) => {
    if (e.key === 'e'||e.key === '-') e.preventDefault();
  });

  amountField.addEventListener('paste', (ev: ClipboardEvent) => {
    ev.preventDefault();
    const text = (ev.clipboardData||((window as any).clipboardData)).getData('text');
    const digits = text.replace(/\D+/g, '').slice(0, 3);
    if (digits.length) {
      amountField.value = String(Math.max(MIN_AMOUNT, Math.min(MAX_AMOUNT, Number(digits))));
    }
    amountField.dispatchEvent(new Event('input', {bubbles:true}));
  });

  syncButtons();
}