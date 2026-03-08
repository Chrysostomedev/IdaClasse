<?php
/**
 * ============================================================
 * FICHIER : includes/footer.php
 * RÔLE    : Pied de page HTML + scripts JS communs
 * ============================================================
 */
?>
        </div><!-- /.page-content -->
    </main><!-- /.main-content -->

    <!-- ===================================================
         FOOTER BARRE EN BAS (optionnel)
         =================================================== -->
    <footer class="app-footer">
        <span>
            &copy; <?= APP_ANNEE ?> <strong><?= APP_NOM ?></strong>
        </span>
        <span>
            Version <?= APP_VERSION ?> — PHP <?= PHP_VERSION ?>
        </span>
        <span>
            MasterClass PHP — 🇨🇮 Côte d'Ivoire
        </span>
    </footer>

    <!-- JavaScript commun -->
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
</body>
</html>