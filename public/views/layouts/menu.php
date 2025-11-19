<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
  <div class="sidebar-header d-flex align-items-center justify-content-start">
    <a href="/SIMA/home" class="navbar-brand">

      <!--Logo start-->
      <div class="logo-main">
        <div class="logo-normal">
          <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/icon.ico" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" onerror="this.onerror=null; this.src='<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?? ''; ?>/assets/images/ImageDefault.png" alt="icono de SIMA">
        </div>
        <div class="logo-mini">
          <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/icon.ico" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" onerror="this.onerror=null; this.src='<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?? ''; ?>/assets/images/ImageDefault.png" alt="icono de SIMA">
        </div>
      </div>
      <!--logo End-->

      <h4 class="logo-title">CMA</h4>
    </a>
    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
      <i class="icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
          <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </i>
    </div>
  </div>
  <div class="sidebar-body pt-0 data-scrollbar">
    <div class="sidebar-list">
      <!-- Sidebar Menu Start -->
      <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="/SIMA/home">
            <i class="icon fa-solid fa-house"></i>
            <span class="item-name">Inicio</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#catalogos" role="button" aria-expanded="false" aria-controls="catalogos">
            <i class="fa-solid fa-list"></i>
            <span class="item-name">Catalogos</span>
            <i class="right-icon">
              <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </i>
          </a>
          <ul class="sub-nav collapse" id="catalogos" data-bs-parent="#sidebar-menu">
            <li class="nav-item">
              <a class="nav-link " href="#">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> C </i>
                <span class="item-name"> Categorias </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="#">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> TP </i>
                <span class="item-name">Tipos de Practica</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="#">
                <i class="icon svg-icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> SC </i>
                <span class="item-name">Sistemas Corporales</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#especialidades" role="button" aria-expanded="false" aria-controls="especialidades">
            <i class="fa-solid fa-glasses"></i>
            <span class="item-name">Especialidades</span>
            <i class="right-icon">
              <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </i>
          </a>
          <ul class="sub-nav collapse" id="especialidades" data-bs-parent="#sidebar-menu">
            <li class="nav-item">
              <a class="nav-link " href="#">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> E </i>
                <span class="item-name"> Especialidades </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="#">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> SE </i>
                <span class="item-name">Sub-Especialidades</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#medicos" role="button" aria-expanded="false" aria-controls="medicos">
            <i class="fa-solid fa-user-doctor">M</i>
            <span class="item-name">Medicos</span>
            <i class="right-icon">
              <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </i>
          </a>
          <ul class="sub-nav collapse" id="medicos" data-bs-parent="#sidebar-menu">
            <li class="nav-item">
              <a class="nav-link " href="/SIMA/usuarios">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"></i>
                <span class="item-name">Medicos</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="/SIMA/auditoria">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> P </i>
                <span class="item-name"> Pagos </span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#mantenimientos" role="button" aria-expanded="false" aria-controls="mantenimientos">
            <i class="icon fa-solid fa-toolbox"></i>
            <span class="item-name">Mantenimientos</span>
            <i class="right-icon">
              <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </i>
          </a>
          <ul class="sub-nav collapse" id="mantenimientos" data-bs-parent="#sidebar-menu">
            <li class="nav-item">
              <a class="nav-link " href="/SIMA/usuarios">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> GU </i>
                <span class="item-name">Gestión de <br> Usuarios</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="/SIMA/auditoria">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> A </i>
                <span class="item-name"> Auditorias </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="/SIMA/servicios-bd">
                <i class="icon">
                  <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                    <g>
                      <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                    </g>
                  </svg>
                </i>
                <i class="sidenav-mini-icon"> BD </i>
                <span class="item-name">Servicios de <br> Bases de Datos</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
      <!-- Sidebar Menu End -->
    </div>
  </div>
  <div class="sidebar-footer"></div>
</aside>




<main class="main-content">
  <div class="position-relative iq-banner">
    <!--Nav Start-->
    <nav class="nav navbar navbar-expand-xl navbar-light iq-navbar">
      <div class="container-fluid navbar-inner">
        <a href="/SIMA/home" class="navbar-brand">

          <!--Logo start-->
          <div class="logo-main">
            <div class="logo-normal">
              <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/icon.ico" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" onerror="this.onerror=null; this.src='<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?? ''; ?>/assets/images/ImageDefault.png" alt="icono de SIMA">
            </div>
            <div class="logo-mini">
              <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/icon.ico" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" onerror="this.onerror=null; this.src='<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?? ''; ?>/assets/images/ImageDefault.png" alt="icono de SIMA">
            </div>
          </div>
          <!--logo End-->
          <h4 class="logo-title">CMA</h4>
        </a>


        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
          <i class="icon">
            <svg width="20px" class="icon-20" viewBox="0 0 24 24">
              <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
            </svg>
          </i>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon">
            <span class="mt-2 navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
          </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
            <li class="nav-item dropdown custom-drop">
              <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/personal/<?php echo $_SESSION['cedula'] . '.' . $_SESSION['extension']; ?>" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" onerror="this.onerror=null; this.src='<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?? ''; ?>/assets/images/ImageDefault.svg'; this.alt='Imagen por defecto';"> -->
                <img src="<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC']; ?>/assets/images/user.png" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" onerror="this.onerror=null; this.src='<?php echo $_ENV['APP_URL'] . $_ENV['BASE_PATH'] . $_ENV['APP_PUBLIC'] ?? ''; ?>/assets/images/ImageDefault.svg'; this.alt='Imagen por defecto';">
                <div class="caption ms-3 d-none d-md-block ">
                  <h6 class="mb-0 caption-title"><?php echo $_SESSION['nombres_apellidos']; ?></h6>
                  <p class="mb-0 caption-sub-title"><?php if ($_SESSION['nivel_acceso'] == 1) {
                                                      echo "Super Administrador (a)";
                                                    } else if ($_SESSION['nivel_acceso'] == 1) {
                                                      echo "Administrador (a)";
                                                    } else if ($_SESSION['nivel_acceso'] == 1) {
                                                      echo "Coordinador (a)";
                                                    } else if ($_SESSION['nivel_acceso'] == 1) {
                                                      echo "Secretario (a)";
                                                    } ?></p>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="/SIMA/logout">Cerrar sesión</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Nav Header Component Start -->
    <div class="iq-navbar-header" style="height: 60px;">

    </div>
    <!-- Nav Header Component End -->
    <!--Nav End-->
  </div>