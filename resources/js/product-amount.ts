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

  minusBtn.addEventListener("click", () => {
    if (!minusBtn.disabled) {
      amountField.value = (Number.parseInt(amountField.value) - 1).toString();
      syncButtons();
    }
  });

  amountField.addEventListener("input", syncButtons);
  syncButtons();
}