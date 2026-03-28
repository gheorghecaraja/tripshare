// FAQ accordion
document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".faq-item");
  
    items.forEach((item) => {
      const question = item.querySelector(".faq-question");
      const answer = item.querySelector(".faq-answer");
      const icon = item.querySelector(".faq-toggle");
  
      if (!question || !answer) return;
  
      question.addEventListener("click", () => {
        const isOpen = answer.classList.contains("open");
  
        // închidem toate celelalte
        items.forEach((other) => {
          const a = other.querySelector(".faq-answer");
          const i = other.querySelector(".faq-toggle");
          if (a && a !== answer) a.classList.remove("open");
          if (i && i !== icon) i.classList.remove("open");
        });
  
        // toggling pe cel curent
        answer.classList.toggle("open", !isOpen);
        if (icon) icon.classList.toggle("open", !isOpen);
      });
    });
  });
  document.querySelectorAll(".faq-button").forEach(btn => {
    btn.addEventListener("click", () => {
      
      const item = btn.parentElement;
      const answer = item.querySelector(".faq-answer");
      const plus = btn.querySelector(".plus");
  
      // Toggle
      answer.style.display = answer.style.display === "block" ? "none" : "block";
      plus.textContent = plus.textContent === "+" ? "−" : "+";
      
    });
  });
  