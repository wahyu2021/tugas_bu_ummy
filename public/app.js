document.addEventListener(
  "click",
  (e) => {
    const inc = e.target.closest("[data-inc]");
    const dec = e.target.closest("[data-dec]");
    if (inc || dec) {
      const input = (inc || dec).parentElement.querySelector('input[type="number"]');
      if (input) {
        const step = Number.parseInt(input.step || "1", 10);
        const min = Number.parseInt(input.min || "0", 10);
        const max = Number.parseInt(input.max || "9999", 10);
        let val = Number.parseInt(input.value || "0", 10);
        val = inc ? Math.min(max, val + step) : Math.max(min, val - step);
        input.value = val;
      }
    }
  },
  { passive: true },
);