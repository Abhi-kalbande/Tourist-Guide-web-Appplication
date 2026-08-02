<?php include __DIR__ . '/../includes/Header.php'; ?>

<main>
    <div class="header">
        <img src="assets/images/banner.dest.jpg" alt="Header Image">
        <h1>CONTACT US</h1>
    </div>

    <div class="container">
        <div class="contact-form">
            <h2>Let's get in touch</h2>
            
            <form action="save_booking.php" method="POST">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="whatsapp">Whatsapp Number *</label>
                <input type="text" id="whatsapp" name="whatsapp" placeholder="Enter your WhatsApp number" required>

                <label for="tour">Tour Name *</label>
                <input type="text" id="tour" name="tour" placeholder="Enter tour name" required>

                <label for="members">Number Of Members *</label>
                <input type="number" id="members" name="members" placeholder="Enter number of members" required>
                
                <center>
                    <button type="submit" class="button">Submit</button>
                </center>
            </form>
        </div>

        <div class="contact-info">
            <h2>You can find us at</h2>
            <p><strong>EMAIL</strong></p>
            <p>traveltales@gmail.com</p>
            <p><strong>LOCATION</strong></p>
            <p> Namaskar Square,Nanded,Maharashtra,India</p>
        </div>
    </div>

    <div class="proprietor">
        <img src="https://cdn.pixabay.com/photo/2024/05/22/05/27/ai-generated-8779693_1280.jpg" alt="Janhavi Totewad">
        <h3>Janhavi Totewad</h3>
        <p><strong>Proprietor</strong></p>
    </div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>