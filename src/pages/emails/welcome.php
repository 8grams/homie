<div class="content">
    <div class="welcome-text">
        Welcome to Homie!
    </div>
    
    <div class="message">
        <p>Hi <?= $this->e($userName) ?>,</p>
        
        <p>Thank you for joining Homie. We're excited to have you on board!</p>
        
        <p>With your new account, you can:</p>
        <ul>
            <li>Access all our features</li>
            <li>Connect with other members</li>
            <li>Get started with your journey</li>
        </ul>
        
        <p>To get started, click the button below:</p>
        
        <a href="<?= $this->e($homieUrl) ?>" class="button">Get Started</a>
        
        <p>If you have any questions, feel free to reach out to our support team.</p>
        
        <p>Best regards,<br>The Homie Team</p>
    </div>
</div>