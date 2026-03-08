/**
 * =====================================================
 * GestionSalles CI — JavaScript principal
 * =====================================================
 * 
 * 💡 ANALOGIE : Ce fichier est comme le "cerveau" de l'interface
 * Il gère les interactions : clics, animations, mode sombre, etc.
 */

// =====================================================
// ATTENDRE QUE LA PAGE SOIT COMPLÈTEMENT CHARGÉE
// =====================================================
/**
 * 💡 ANALOGIE : C'est comme attendre que tous les invités
 * soient arrivés avant de commencer la fête !
 * 
 * DOMContentLoaded = événement déclenché quand le HTML est prêt
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // =====================================================
    // GESTION DE LA SIDEBAR MOBILE
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme un tiroir qu'on ouvre/ferme
     * Sur mobile, la sidebar est cachée par défaut
     */
    
    // Récupérer les éléments du DOM (Document Object Model)
    const burgerBtn = document.getElementById('burgerBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    // Si le bouton burger existe (on est sur une page avec sidebar)
    if (burgerBtn && sidebar) {
        
        // Écouter le clic sur le bouton burger
        burgerBtn.addEventListener('click', function() {
            // Toggle = basculer (ajouter si absent, retirer si présent)
            sidebar.classList.toggle('open');
            
            if (overlay) {
                overlay.classList.toggle('active');
            }
        });
        
        // Fermer la sidebar si on clique sur l'overlay
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        }
    }
    
    // =====================================================
    // GESTION DU MODE SOMBRE
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme un interrupteur de lumière
     * On sauvegarde la préférence dans localStorage
     * (localStorage = mémoire du navigateur qui persiste)
     */
    
    const themeToggle = document.getElementById('themeToggle');
    
    if (themeToggle) {
        
        // Vérifier si l'utilisateur avait déjà choisi un thème
        // localStorage.getItem() = lire une valeur sauvegardée
        const savedTheme = localStorage.getItem('theme');
        
        // Si le thème sauvegardé est 'dark', l'appliquer
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            updateThemeIcon(true);
        }
        
        // Écouter le clic sur le bouton de thème
        themeToggle.addEventListener('click', function() {
            // Toggle = basculer entre clair et sombre
            document.body.classList.toggle('dark-mode');
            
            // Vérifier si le mode sombre est actif
            const isDark = document.body.classList.contains('dark-mode');
            
            // Sauvegarder le choix dans localStorage
            // localStorage.setItem() = sauvegarder une valeur
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            
            // Mettre à jour l'icône
            updateThemeIcon(isDark);
        });
    }
    
    /**
     * Mettre à jour l'icône du bouton de thème
     * @param {boolean} isDark - true si mode sombre actif
     */
    function updateThemeIcon(isDark) {
        const icon = themeToggle.querySelector('i');
        if (icon) {
            // Changer l'icône selon le mode
            icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }
    }
    
    // =====================================================
    // VALIDATION DES FORMULAIRES
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme un contrôleur de sécurité
     * qui vérifie que tout est en ordre avant de laisser passer
     */
    
    // Formulaire d'inscription
    const formInscription = document.querySelector('form[action=""][method="post"]');
    const mdpInput = document.getElementById('reg-mdp');
    const confirmInput = document.getElementById('reg-confirm');
    
    // Vérifier la correspondance des mots de passe en temps réel
    if (confirmInput && mdpInput) {
        confirmInput.addEventListener('input', function() {
            // Si les mots de passe ne correspondent pas
            if (this.value && mdpInput.value !== this.value) {
                this.style.borderColor = '#ef4444'; // Rouge
                this.style.boxShadow = '0 0 0 4px rgba(239,68,68,0.1)';
            } 
            // Si ils correspondent
            else if (this.value) {
                this.style.borderColor = '#10b981'; // Vert
                this.style.boxShadow = '0 0 0 4px rgba(16,185,129,0.1)';
            } 
            // Si le champ est vide
            else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });
    }
    
    // =====================================================
    // ANIMATIONS AU SCROLL
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme des éléments qui apparaissent
     * progressivement quand on descend dans la page
     */
    
    // Observer pour détecter quand un élément entre dans la vue
    const observer = new IntersectionObserver(
        function(entries) {
            // Pour chaque élément observé
            entries.forEach(function(entry) {
                // Si l'élément est visible
                if (entry.isIntersecting) {
                    // Ajouter une classe pour l'animation
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        },
        {
            threshold: 0.1 // Déclencher quand 10% de l'élément est visible
        }
    );
    
    // Observer toutes les cartes statistiques
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(function(card, index) {
        // Style initial (invisible et décalé vers le bas)
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease ' + (index * 0.1) + 's';
        
        // Commencer à observer
        observer.observe(card);
    });
    
    // =====================================================
    // AUTO-MASQUER LES ALERTES
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme une notification qui disparaît
     * automatiquement après quelques secondes
     */
    
    const alertes = document.querySelectorAll('.alert-auth.visible');
    alertes.forEach(function(alerte) {
        // Après 5 secondes (5000 millisecondes)
        setTimeout(function() {
            // Ajouter une transition de fondu
            alerte.style.transition = 'opacity 0.5s ease';
            alerte.style.opacity = '0';
            
            // Après la transition, masquer complètement
            setTimeout(function() {
                alerte.classList.remove('visible');
            }, 500);
        }, 5000);
    });
    
    // =====================================================
    // CONFIRMATION AVANT SUPPRESSION
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme demander "Es-tu sûr ?"
     * avant de faire quelque chose d'irréversible
     */
    
    const btnSupprimer = document.querySelectorAll('[data-action="supprimer"]');
    btnSupprimer.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            // Demander confirmation
            const confirmation = confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');
            
            // Si l'utilisateur annule
            if (!confirmation) {
                e.preventDefault(); // Empêcher l'action
            }
        });
    });
    
    // =====================================================
    // RECHERCHE EN TEMPS RÉEL DANS LES TABLEAUX
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme un filtre qui cache/montre
     * les lignes selon ce qu'on tape
     */
    
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                // Récupérer tout le texte de la ligne
                const text = row.textContent.toLowerCase();
                
                // Afficher ou cacher selon la correspondance
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // =====================================================
    // TOOLTIPS (INFO-BULLES)
    // =====================================================
    /**
     * 💡 ANALOGIE : Comme des petites bulles d'aide
     * qui apparaissent au survol
     */
    
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(function(element) {
        element.addEventListener('mouseenter', function() {
            const text = this.getAttribute('data-tooltip');
            
            // Créer l'élément tooltip
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = text;
            tooltip.style.cssText = `
                position: absolute;
                background: #1f2937;
                color: white;
                padding: 0.5rem 0.75rem;
                border-radius: 6px;
                font-size: 0.85rem;
                z-index: 9999;
                pointer-events: none;
            `;
            
            document.body.appendChild(tooltip);
            
            // Positionner le tooltip
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
            
            // Sauvegarder la référence
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
    
});

// =====================================================
// FONCTIONS GLOBALES (accessibles partout)
// =====================================================

/**
 * Basculer la sidebar (appelée depuis le HTML)
 * 💡 ANALOGIE : Comme une fonction qu'on peut appeler
 * depuis n'importe où dans le code
 */
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar) {
        sidebar.classList.toggle('open');
    }
    
    if (overlay) {
        overlay.classList.toggle('active');
    }
}

/**
 * Basculer entre les panneaux login/inscription
 * @param {string} panneau - 'login' ou 'inscription'
 */
function basculerVers(panneau) {
    const panels = document.getElementById('authPanels');
    const tabLogin = document.getElementById('tab-login');
    const tabReg = document.getElementById('tab-inscription');
    
    if (panneau === 'inscription') {
        panels.classList.add('show-register');
        tabReg.classList.add('active');
        tabLogin.classList.remove('active');
    } else {
        panels.classList.remove('show-register');
        tabLogin.classList.add('active');
        tabReg.classList.remove('active');
    }
}

/**
 * Afficher une notification toast
 * @param {string} message - Le message à afficher
 * @param {string} type - 'success', 'error', 'info'
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#6366f1'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Retirer après 3 secondes
    setTimeout(function() {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, 3000);
}

// Ajouter les animations CSS pour les toasts
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
