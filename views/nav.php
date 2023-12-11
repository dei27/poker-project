<nav class="navbar sticky-top">
    <div class="container-fluid">
    <?php
        $currentFile = basename($_SERVER["SCRIPT_FILENAME"]);
        $textClass = ($currentFile == 'torneos.php') ? 'text-white' : 'text-dark';
        $textClassTorneos = ($currentFile == 'torneos.php') ? 'text-dark btn btn-light' : 'text-white btn btn-dark';
        ?>

        <a class="navbar-brand <?php echo $textClass; ?> fs-3" href="./guest.php"><i class="bi bi-house-door-fill fs-2"></i></a>
        <a class="navbar-brand fs-5 <?php echo $textClassTorneos; ?>" href="torneos.php">Torneos</a>
        <!-- <a class="navbar-toggler border-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <i class="bi bi-list fs-1 text-secondary"></i>
        </a>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Poker DTV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex align-self-center">
                <div class="row">
                    <div class="col-12">
                        <a href="https://www.linkedin.com/in/dei81/" target="_blank">Torneos</a>
                    </div>
                    <div class="col-12">
                        <a href="mailto:deivicb81@gmail.com?Subject=I%20want%20some%20information" target="_blank">Patrocinadores</a>
                    </div>
                    <div class="col-12">
                        <a href="https://github.com/dei27" target="_blank">La Callejera</a>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</nav>