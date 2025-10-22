<link rel="stylesheet" href="css/seccion-faq.css">
<section class="faq-section">
    <div class="faq-container">
        <div class="faq-header">
            <div class="faq-subtitle">Noticias y Blogs</div>
            <h2 class="faq-title">Preguntas <span class="highlight">Frecuentes</span></h2>
        </div>

        <div class="faq-list">
            <div class="faq-item">
                <div class="faq-question">
                    <span>Preguntas</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Preguntas</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Preguntas</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    <span>Preguntas</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const faqItems = document.querySelectorAll(".faq-item");

    faqItems.forEach(item => {
        item.addEventListener("click", () => {
            item.classList.toggle("active");
            const toggle = item.querySelector(".faq-toggle");
            toggle.textContent = item.classList.contains("active") ? "−" : "+";
        });
    });
</script>