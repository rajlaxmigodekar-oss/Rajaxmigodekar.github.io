<footer>
    <div class="footer-container">
        <div class="footer-section about">
            <h2>IdolStore</h2>
            <p>Your one-stop shop for divine idols and religious artifacts.</p>
        </div>
        <div class="footer-section links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Shop</a></li>
                
                <li><a href="contact_form.php">Contact</a></li>
            </ul>
        </div>
        <div class="footer-section contact">
            <h2>Contact Us</h2>
            <p>Email: omkara_murtis@gmail.com</p>
            <p>Phone: +91 8263819393</p>
            <p>Address: Pendkhale,Rajapur,Dist.Ratnagiri</p>
        </div>
        <div class="footer-section social">
            <h2>Follow Us</h2>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 IdolStore. All rights reserved.</p>
    </div>
</footer>

<style>
    footer {
        background: #333;
        color: white;
        padding: 20px 0;
        text-align: center;
    }
    .footer-container {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: auto;
    }
    .footer-section {
        flex: 1;
        padding: 10px;
        min-width: 200px;
    }
    .footer-section h2 {
        font-size: 20px;
        margin-bottom: 10px;
    }
    .footer-section p, .footer-section ul, .footer-section a {
        font-size: 14px;
        color: white;
        text-decoration: none;
    }
    .footer-section ul {
        list-style: none;
        padding: 0;
    }
    .footer-section ul li {
        margin: 5px 0;
    }
    .footer-section a:hover {
        color: #ff9800;
    }
    .social a {
        font-size: 20px;
        margin: 0 10px;
        color: white;
        transition: 0.3s;
    }
    .social a:hover {
        color: #ff9800;
    }
    .footer-bottom {
        margin-top: 20px;
        font-size: 12px;
        border-top: 1px solid #555;
        padding-top: 10px;
    }
    @media screen and (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- Font Awesome for icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
