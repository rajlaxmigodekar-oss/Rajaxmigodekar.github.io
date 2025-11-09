
<!DOCTYPE html>
<html lang="en">
<head>
    <style type="text/css">
    .about-us {
    font-family: 'Poppins', sans-serif;
    color: #333;
    text-align: center;
    padding: 40px 10%;
    background: linear-gradient(45deg, #FBE8E8, #E6D6F2, #B0E2FF);
}

.about-banner {
    background: url('banner.jpg') center/cover no-repeat;
    color: white;
    padding: 60px 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.about-banner h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.about-banner p {
    font-size: 18px;
    font-weight: 300;
}

.about-content {
    margin-top: 40px;
}

.about-section {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 40px;
}

.about-section.reverse {
    flex-direction: row-reverse;
}

.about-section img {
    width: 50%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.about-section .text {
    width: 50%;
    text-align: left;
}

.about-section h2 {
    font-size: 28px;
    margin-bottom: 10px;
    color: #D7263D;
}

.about-section p, .about-section ul {
    font-size: 18px;
    font-weight: 300;
}

.about-section ul {
    list-style: none;
    padding-left: 0;
}

.about-section li {
    margin-bottom: 5px;
    font-size: 16px;
    padding-left: 20px;
    position: relative;
}

.about-section li::before {
    content: "✔";
    position: absolute;
    left: 0;
    color: #2ECC71;
    font-size: 18px;
}

.contact-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    margin-top: 30px;
}

.contact-section h2 {
    color: #D7263D;
}

.contact-section p {
    font-size: 18px;
    font-weight: 400;
}

   </style>
   </head>
   </body> 
   <div class="about-us">
    <div class="about-banner">
        <h1>About Omkara Murtis</h1>
        <p>Discover the divine elegance of handcrafted idols</p>
    </div>

    <div class="about-content">
        <div class="about-section">
            <img src="mission.jpg" alt="Our Mission">
            <div class="text">
                <h2>🌟 Our Mission</h2>
                <p>Our mission is to bring devotion closer to your home by providing meticulously crafted idols that radiate positivity, peace, and spiritual energy.</p>
            </div>
        </div>

        <div class="about-section reverse">
            <div class="text">
                <h2>🔮 Our Vision</h2>
                <p>We aim to preserve India's rich spiritual heritage by making sacred idols accessible to devotees worldwide.</p>
            </div>
            <img src="vision.jpg" alt="Our Vision">
        </div>

        <div class="about-section">
            <img src="why-choose-us.jpg" alt="Why Choose Us">
            <div class="text">
                <h2>💎 Why Choose Us?</h2>
                <ul>
                    <li>High-quality handcrafted idols made from premium materials</li>
                    <li>100% authentic and ethically sourced products</li>
                    <li>Cash on Delivery (COD) available for hassle-free shopping</li>
                    <li>Secure packaging and timely delivery</li>
                </ul>
            </div>
        </div>

        <div class="about-section reverse">
            <div class="text">
                <h2>🎨 Our Craftsmanship</h2>
                <p>Our skilled artisans create each idol with precision, using materials like brass, marble, and wood to ensure longevity and divine beauty.</p>
            </div>
            <img src="craftsmanship.jpg" alt="Our Craftsmanship">
        </div>

        <div class="about-section">
            <img src="customer-reviews.jpg" alt="What Our Customers Say">
            <div class="text">
                <h2>💬 What Our Customers Say</h2>
                <p>"Absolutely love the quality and design of the idols! Thank you, Omkara Murtis, for bringing such divine art into our lives." - <i>A Happy Customer</i></p>
            </div>
        </div>

        <div class="contact-section">
            <h2>📞 Get in Touch</h2>
            <p>Have questions? Reach out to us at <b>support@omkaramurtis.com</b> or call us at <b>+91 98765 43210</b>.</p>
        </div>
    </div>
</div>
</body>
</html>
