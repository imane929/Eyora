<?php
// Messages d'erreur/succès
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="container">
    <!-- Hero Contact -->
    <section class="contact-hero" style="
        text-align: center;
        margin-bottom: 60px;
        padding: 60px 0;
        background: var(--bg-secondary);
        border-radius: 30px;
    ">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">Contactez-nous</h1>
        <p style="font-size: 1.2rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
            Une question ? Un projet ? Nous sommes à votre écoute pour répondre à toutes vos demandes.
        </p>
    </section>

    <div style="
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin-bottom: 80px;
    ">
        <!-- Formulaire de contact -->
        <div class="contact-form-container" style="
            background: var(--card-bg);
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
        ">
            <h2 style="margin-bottom: 30px; display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-envelope" style="color: var(--primary-color);"></i>
                Envoyez-nous un message
            </h2>
            
            <?php if ($success): ?>
            <div style="
                background: rgba(var(--success-color-rgb), 0.1);
                color: var(--success-color);
                padding: 15px 20px;
                border-radius: 10px;
                margin-bottom: 25px;
                border-left: 4px solid var(--success-color);
                display: flex;
                align-items: center;
                gap: 12px;
            ">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div style="
                background: rgba(var(--error-color-rgb), 0.1);
                color: var(--error-color);
                padding: 15px 20px;
                border-radius: 10px;
                margin-bottom: 25px;
                border-left: 4px solid var(--error-color);
                display: flex;
                align-items: center;
                gap: 12px;
            ">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php" style="display: flex; flex-direction: column; gap: 24px;">
                <input type="hidden" name="page" value="contact">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="
                            display: block;
                            margin-bottom: 8px;
                            font-weight: 600;
                            color: var(--text-primary);
                        ">Votre nom *</label>
                        <input type="text" 
                               name="name" 
                               required
                               style="
                                    width: 100%;
                                    padding: 14px 20px;
                                    border: 2px solid var(--border-color);
                                    border-radius: 10px;
                                    font-family: 'Open Sans', sans-serif;
                                    font-size: 1rem;
                                    background: var(--bg-primary);
                                    color: var(--text-primary);
                                    transition: all var(--transition);
                               "
                               onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 3px rgba(var(--primary-color-rgb), 0.1)'"
                               onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'">
                    </div>
                    
                    <div>
                        <label style="
                            display: block;
                            margin-bottom: 8px;
                            font-weight: 600;
                            color: var(--text-primary);
                        ">Votre email *</label>
                        <input type="email" 
                               name="email" 
                               required
                               style="
                                    width: 100%;
                                    padding: 14px 20px;
                                    border: 2px solid var(--border-color);
                                    border-radius: 10px;
                                    font-family: 'Open Sans', sans-serif;
                                    font-size: 1rem;
                                    background: var(--bg-primary);
                                    color: var(--text-primary);
                                    transition: all var(--transition);
                               "
                               onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 3px rgba(var(--primary-color-rgb), 0.1)'"
                               onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'">
                    </div>
                </div>
                
                <div>
                    <label style="
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 600;
                        color: var(--text-primary);
                    ">Sujet *</label>
                    <input type="text" 
                           name="subject" 
                           required
                           style="
                                width: 100%;
                                padding: 14px 20px;
                                border: 2px solid var(--border-color);
                                border-radius: 10px;
                                font-family: 'Open Sans', sans-serif;
                                font-size: 1rem;
                                background: var(--bg-primary);
                                color: var(--text-primary);
                                transition: all var(--transition);
                           "
                           onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 3px rgba(var(--primary-color-rgb), 0.1)'"
                           onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'">
                </div>
                
                <div>
                    <label style="
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 600;
                        color: var(--text-primary);
                    ">Votre message *</label>
                    <textarea name="message" 
                              rows="6"
                              required
                              style="
                                width: 100%;
                                padding: 14px 20px;
                                border: 2px solid var(--border-color);
                                border-radius: 10px;
                                font-family: 'Open Sans', sans-serif;
                                font-size: 1rem;
                                background: var(--bg-primary);
                                color: var(--text-primary);
                                resize: vertical;
                                transition: all var(--transition);
                              "
                              onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 3px rgba(var(--primary-color-rgb), 0.1)'"
                              onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'"></textarea>
                </div>
                
                <button type="submit" 
                        name="contact_submit"
                        style="
                            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
                            color: white;
                            border: none;
                            padding: 18px 40px;
                            border-radius: 50px;
                            font-family: 'Poppins', sans-serif;
                            font-weight: 600;
                            font-size: 1.1rem;
                            cursor: pointer;
                            transition: all var(--transition);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 12px;
                            box-shadow: 0 8px 25px rgba(var(--primary-color-rgb), 0.3);
                        "
                        onmouseenter="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(var(--primary-color-rgb), 0.4)'"
                        onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(var(--primary-color-rgb), 0.3)'">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer le message
                </button>
            </form>
        </div>

        <!-- Informations de contact -->
        <div class="contact-info" style="
            background: var(--card-bg);
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
        ">
            <h2 style="margin-bottom: 30px; display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-info-circle" style="color: var(--primary-color);"></i>
                Nos coordonnées
            </h2>
            
            <div style="display: flex; flex-direction: column; gap: 25px; margin-bottom: 40px;">
                <div style="
                    display: flex;
                    align-items: flex-start;
                    gap: 20px;
                    padding: 20px;
                    background: rgba(var(--primary-color-rgb), 0.05);
                    border-radius: 12px;
                    transition: all var(--transition);
                " onmouseenter="this.style.transform='translateY(-5px)'; this.style.background='rgba(var(--primary-color-rgb), 0.1)'"
                   onmouseleave="this.style.transform='translateY(0)'; this.style.background='rgba(var(--primary-color-rgb), 0.05)'">
                    <div style="
                        width: 50px;
                        height: 50px;
                        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 8px; color: var(--text-primary);">Notre adresse</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 10px; line-height: 1.6;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 10px; color: var(--primary-color);"></i>
                            123 Avenue ABC<br>
                            Mohammedia, Maroc
                        </p>
                    </div>
                </div>
                
                <div style="
                    display: flex;
                    align-items: flex-start;
                    gap: 20px;
                    padding: 20px;
                    background: rgba(var(--primary-color-rgb), 0.05);
                    border-radius: 12px;
                    transition: all var(--transition);
                " onmouseenter="this.style.transform='translateY(-5px)'; this.style.background='rgba(var(--primary-color-rgb), 0.1)'"
                   onmouseleave="this.style.transform='translateY(0)'; this.style.background='rgba(var(--primary-color-rgb), 0.05)'">
                    <div style="
                        width: 50px;
                        height: 50px;
                        background: linear-gradient(135deg, #10b981, #34d399);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 8px; color: var(--text-primary);">Téléphone</h3>
                        <p style="color: var(--text-secondary); margin: 0; line-height: 1.6;">
                            +212 6 23 45 67 89<br>
                            Lun-Ven : 9h-19h
                        </p>
                    </div>
                </div>
                
                <div style="
                    display: flex;
                    align-items: flex-start;
                    gap: 20px;
                    padding: 20px;
                    background: rgba(var(--primary-color-rgb), 0.05);
                    border-radius: 12px;
                    transition: all var(--transition);
                " onmouseenter="this.style.transform='translateY(-5px)'; this.style.background='rgba(var(--primary-color-rgb), 0.1)'"
                   onmouseleave="this.style.transform='translateY(0)'; this.style.background='rgba(var(--primary-color-rgb), 0.05)'">
                    <div style="
                        width: 50px;
                        height: 50px;
                        background: linear-gradient(135deg, #8b5cf6, #a78bfa);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 8px; color: var(--text-primary);">Email</h3>
                        <p style="color: var(--text-secondary); margin: 0; line-height: 1.6;">
                            contact@eyora.com<br>
                            Réponse sous 24h
                        </p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-clock" style="color: var(--primary-color);"></i>
                    Horaires d'ouverture
                </h3>
                <div style="
                    background: var(--bg-tertiary);
                    border-radius: 12px;
                    overflow: hidden;
                ">
                    <div style="
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        border-bottom: 1px solid var(--border-color);
                    ">
                        <div style="padding: 15px 20px; font-weight: 600;">Jour</div>
                        <div style="padding: 15px 20px; font-weight: 600;">Horaires</div>
                    </div>
                    
                    <?php
                    $horaires = [
                        ['Lundi - Vendredi', '9h00 - 19h00'],
                        ['Samedi', '10h00 - 18h00'],
                        ['Dimanche', 'Fermé']
                    ];
                    
                    foreach ($horaires as $horaire):
                    ?>
                    <div style="
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        border-bottom: 1px solid var(--border-color);
                        transition: background-color var(--transition);
                    " onmouseenter="this.style.backgroundColor='rgba(var(--primary-color-rgb), 0.05)'"
                       onmouseleave="this.style.backgroundColor='transparent'">
                        <div style="padding: 15px 20px; color: var(--text-primary);"><?php echo $horaire[0]; ?></div>
                        <div style="padding: 15px 20px; color: var(--text-secondary);"><?php echo $horaire[1]; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte -->
    <div style="
        background: var(--card-bg);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-bottom: 60px;
    ">
        <div style="
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 25px 30px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-tertiary);
        ">
            <i class="fas fa-map-marked-alt" style="color: var(--primary-color); font-size: 1.5rem;"></i>
            <h3 style="margin: 0; color: var(--text-primary);">Nous trouver</h3>
        </div>
        
        <div style="
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        ">
            <div>
                <h4 style="margin-bottom: 20px; color: var(--text-primary);">Notre boutique Eyora</h4>
                <p style="color: var(--text-secondary); margin-bottom: 25px; line-height: 1.8;">
                    Notre boutique se situe au cœur de Mohammedia . 
                    Venez découvrir nos collections en exclusivité, bénéficier de conseils personnalisés 
                    et profiter d'un service sur mesure.
                </p>
                
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-subway" style="color: var(--primary-color);"></i>
                        <span style="color: var(--text-secondary);">
                            Métro : Lignes 1, 2, 6
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-bus" style="color: var(--primary-color);"></i>
                        <span style="color: var(--text-secondary);">
                            Bus : N1, N2, N3, N4
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-parking" style="color: var(--primary-color);"></i>
                        <span style="color: var(--text-secondary);">
                            Parking : Parking ABC
                        </span>
                    </div>
                </div>
            </div>
            
            <div style="
                background: var(--bg-tertiary);
                height: 300px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text-muted);
                flex-direction: column;
                gap: 20px;
            ">
                <i class="fas fa-map" style="font-size: 4rem; opacity: 0.5;"></i>
                <p>Carte interactive</p>
                <button style="
                    background: var(--primary-color);
                    color: white;
                    border: none;
                    padding: 12px 28px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-family: 'Poppins', sans-serif;
                    font-weight: 500;
                    transition: all var(--transition);
                "
                onmouseenter="this.style.backgroundColor='var(--primary-dark)'; this.style.transform='translateY(-2px)'"
                onmouseleave="this.style.backgroundColor='var(--primary-color)'; this.style.transform='translateY(0)'">
                    <i class="fas fa-external-link-alt"></i>
                    Ouvrir dans Google Maps
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Validation du formulaire
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[method="POST"]');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = form.querySelector('input[name="name"]');
            const email = form.querySelector('input[name="email"]');
            const subject = form.querySelector('input[name="subject"]');
            const message = form.querySelector('textarea[name="message"]');
            
            let isValid = true;
            
            // Validation simple
            if (!name.value.trim()) {
                showFieldError(name, 'Le nom est requis');
                isValid = false;
            } else {
                clearFieldError(name);
            }
            
            if (!email.value.trim() || !isValidEmail(email.value)) {
                showFieldError(email, 'Un email valide est requis');
                isValid = false;
            } else {
                clearFieldError(email);
            }
            
            if (!subject.value.trim()) {
                showFieldError(subject, 'Le sujet est requis');
                isValid = false;
            } else {
                clearFieldError(subject);
            }
            
            if (!message.value.trim()) {
                showFieldError(message, 'Le message est requis');
                isValid = false;
            } else {
                clearFieldError(message);
            }
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Veuillez corriger les erreurs dans le formulaire', 'error');
            }
        });
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function showFieldError(field, message) {
        field.style.borderColor = 'var(--error-color)';
        field.style.boxShadow = '0 0 0 3px rgba(var(--error-color-rgb), 0.1)';
        
        // Supprimer les erreurs précédentes
        clearFieldError(field);
        
        // Ajouter message d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.style.color = 'var(--error-color)';
        errorDiv.style.fontSize = '0.85rem';
        errorDiv.style.marginTop = '5px';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        
        field.parentNode.appendChild(errorDiv);
    }
    
    function clearFieldError(field) {
        field.style.borderColor = '';
        field.style.boxShadow = '';
        
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
});
</script>