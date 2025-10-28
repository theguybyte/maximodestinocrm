<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Travelwp
 */

get_header();

?>
<style>

.top_site_main {
    display: none !important;
}

.error-404-wrapper {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, #001d3a 0%, #03519f 100%);
    position: relative;
    overflow: hidden;
}

.error-404-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    animation: clouds 20s ease-in-out infinite;
}

@keyframes clouds {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(30px); }
}

.error-404-content {
    text-align: center;
    position: relative;
    z-index: 1;
    max-width: 600px;
    background: rgba(255, 255, 255, 0.95);
    padding: 60px 40px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.error-404-number {
    font-size: 140px;
    font-weight: 800;
    line-height: 1;
    margin: 0;
    background: linear-gradient(135deg, #001d3a 0%, #03519f 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.error-404-title {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin: 20px 0 15px;
}

.error-404-description {
    font-size: 18px;
    color: #4a5568;
    margin-bottom: 35px;
    line-height: 1.6;
}

.error-404-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: center;
    margin-top: 30px;
}

.error-404-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 40px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 50px;
    transition: all 0.3s ease;
    min-width: 200px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.error-404-btn-primary {
    background: #BC1115;
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(188, 17, 21, 0.3);
}

.error-404-btn-primary:hover {
    background: #9a0e11;
    box-shadow: 0 10px 25px rgba(188, 17, 21, 0.5);
    color: #ffffff;
}

.error-404-btn-secondary {
    background: #ffffff;
    color: #001D3A;
    border: 2px solid #001D3A;
}

.error-404-btn-secondary:hover {
    background: #001D3A;
    color: #ffffff;
    border: 2px solid #001D3A;
}

.error-404-search {
    width: 100%;
    max-width: 400px;
    margin-top: 20px;
}

.error-404-search .search-form {
    display: flex;
    gap: 10px;
}

.error-404-search .search-field {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid #e2e8f0;
    border-radius: 50px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.error-404-search .search-field:focus {
    outline: none;
    border-color: #4facfe;
    box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
}

.error-404-search .search-submit {
    padding: 14px 30px;
    background: #4facfe;
    color: #ffffff;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.error-404-search .search-submit:hover {
    background: #00f2fe;
    transform: scale(1.05);
}

.error-404-icon-container {
    margin-bottom: 20px;
    animation: sway 4s ease-in-out infinite;
}

@keyframes sway {
    0%, 100% { transform: rotate(-5deg); }
    50% { transform: rotate(5deg); }
}

.error-404-icon {
    font-size: 100px;
    display: inline-block;
}

.error-404-decoration {
    position: absolute;
    font-size: 40px;
    opacity: 0.3;
    animation: floating 6s ease-in-out infinite;
}

.decoration-1 { top: 10%; left: 10%; animation-delay: 0s; }
.decoration-2 { top: 20%; right: 15%; animation-delay: 1s; }
.decoration-3 { bottom: 15%; left: 15%; animation-delay: 2s; }
.decoration-4 { bottom: 20%; right: 10%; animation-delay: 3s; }

@keyframes floating {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(10deg); }
}

@media (max-width: 768px) {
    .error-404-number {
        font-size: 100px;
    }
    
    .error-404-title {
        font-size: 26px;
    }
    
    .error-404-description {
        font-size: 16px;
    }
    
    .error-404-content {
        padding: 40px 30px;
    }
    
    .error-404-decoration {
        font-size: 30px;
    }
}
</style>

<?php do_action( 'travelwp_wrapper_banner_heading' ); ?>

<div class="error-404-wrapper">
    <span class="error-404-decoration decoration-1">✈️</span>
    <span class="error-404-decoration decoration-2">🏝️</span>
    <span class="error-404-decoration decoration-3">🧳</span>
    <span class="error-404-decoration decoration-4">🗺️</span>
    
    <div class="error-404-content">
        <div class="error-404-icon-container">
            <span class="error-404-icon">🧭</span>
        </div>
        <h1 class="error-404-number">404</h1>
        <h2 class="error-404-title">¡Ups! Página no encontrada</h2>
        <p class="error-404-description">
            Parece que esta página no existe. ¡No te preocupes, te ayudamos a encontrar lo que buscás!
        </p>
        
        <div class="error-404-actions">
            <a href="<?php echo esc_url( home_url( '/productos' ) ); ?>" class="error-404-btn error-404-btn-primary">
                Ver Productos
            </a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404-btn error-404-btn-secondary">
                Volver al Inicio
            </a>
        </div>
        
    </div>
</div>

<?php
get_footer();
?>