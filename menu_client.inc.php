
        <!-- Si utilisateur non connecté -->
        <?php if(!isset($_SESSION['client_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="inscription.php">Inscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="connexion.php">Connexion</a>
          </li>

        <!-- Si utilisateur connecté -->
        <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="menuClient" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Espace client
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuClient">
              <li><a class="dropdown-item" href="visualiser_demandes.php">Visualiser mes demandes</a></li>
              <li><a class="dropdown-item" href="profil.php">Mon profil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="deconnexion.php">Déconnexion</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>

