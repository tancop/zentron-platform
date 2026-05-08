const itemAmountInputs = document.querySelectorAll<HTMLInputElement>('.item-amount');

itemAmountInputs.forEach((input) => {
  input.setAttribute('min', '1');

  const clamp = () => {
    const val = Number.parseInt(input.value || '1');
    if (Number.isNaN(val) || (val < 1)) input.value = '1';
  };

  input.addEventListener('input', clamp);
  input.addEventListener('change', clamp);
  input.addEventListener('keydown', (e) => {
    if (e.key === 'e'||e.key === '-') e.preventDefault();
  });
});

document.addEventListener('DOMContentLoaded', () =>{
  itemAmountInputs.forEach((i) => {
    const val = Number.parseInt(i.value || '1');
    if (Number.isNaN(val) || val < 1) i.value = '1';
    i.setAttribute('min', '1');
  });
});
