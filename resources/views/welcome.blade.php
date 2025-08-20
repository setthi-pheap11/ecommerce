<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Ecommerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #FF6B35;
            --secondary-orange: #FF8C42;
            --light-orange: #FFB366;
            --dark-orange: #E55A2B;
            --orange-light-bg: #FFF8F5;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange)) !important;
            box-shadow: 0 2px 10px rgba(255, 107, 53, 0.2);
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--orange-light-bg), #FFF);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-orange), var(--secondary-orange));
            clip-path: polygon(20% 0%, 100% 0%, 100% 100%, 0% 100%);
            opacity: 0.1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-orange), var(--primary-orange));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .price {
            color: var(--primary-orange);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .sale-price {
            color: #dc3545;
            text-decoration: line-through;
            font-size: 0.9rem;
            margin-left: 10px;
        }

        .category-badge {
            background: linear-gradient(135deg, var(--light-orange), var(--secondary-orange));
            color: white;
            border-radius: 15px;
            padding: 5px 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .footer {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 50px 0 20px;
        }

        .footer h5 {
            color: var(--light-orange);
            margin-bottom: 20px;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--light-orange);
        }

        .cart-count {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            font-size: 0.8rem;
            padding: 2px 6px;
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .api-demo {
            background: var(--orange-light-bg);
            border-radius: 15px;
            padding: 30px;
            margin: 50px 0;
        }

        .api-demo h3 {
            color: var(--dark-orange);
            margin-bottom: 20px;
        }

        .api-endpoint {
            background: white;
            border-left: 4px solid var(--primary-orange);
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: monospace;
        }

        .method-badge {
            background: var(--primary-orange);
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 0.8rem;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-store"></i> Laravel Ecommerce
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#api">API Documentation</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="#cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count" id="cartCount">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Admin Panel</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Welcome to Our <span style="color: var(--primary-orange);">Orange-Themed</span> Ecommerce Store
                    </h1>
                    <p class="lead mb-4">
                        Discover amazing products with our Laravel-powered ecommerce platform. 
                        Built with modern APIs, Docker support, and a beautiful orange design.
                    </p>
                    <div class="d-flex gap-3">
                        <button class="btn btn-primary btn-lg" onclick="window.location.href='#products'">
                            <i class="fas fa-shopping-bag"></i> Shop Now
                        </button>
                        <button class="btn btn-outline-secondary btn-lg" onclick="window.location.href='#api'">
                            <i class="fas fa-code"></i> View API
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <i class="fas fa-shopping-cart" style="font-size: 200px; color: var(--light-orange); opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">Why Choose Our Platform?</h2>
                    <p class="lead text-muted">Built with modern technologies for the best experience</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-api fa-3x mb-3" style="color: var(--primary-orange);"></i>
                            <h5 class="card-title">RESTful API</h5>
                            <p class="card-text">Complete API endpoints for all ecommerce operations including authentication, products, cart, and orders.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fab fa-docker fa-3x mb-3" style="color: var(--primary-orange);"></i>
                            <h5 class="card-title">Docker Support</h5>
                            <p class="card-text">Fully containerized with Docker Compose for easy development and deployment.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-shield-alt fa-3x mb-3" style="color: var(--primary-orange);"></i>
                            <h5 class="card-title">Admin Panel</h5>
                            <p class="card-text">Comprehensive admin dashboard for managing products, orders, categories, and users.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Featured Products</h2>
                    <p class="lead text-muted">Check out our latest and greatest products</p>
                </div>
            </div>
            <div class="row" id="productsList">
                <!-- Products will be loaded here via API -->
                <div class="col-12 text-center">
                    <div class="spinner-border" style="color: var(--primary-orange);" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading products...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- API Documentation Section -->
    <section id="api" class="py-5">
        <div class="container">
            <div class="api-demo">
                <h3><i class="fas fa-code"></i> API Documentation</h3>
                <p>This ecommerce platform provides a complete RESTful API. Here are some key endpoints:</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Authentication</h5>
                        <div class="api-endpoint">
                            <span class="method-badge">POST</span> /api/register
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge">POST</span> /api/login
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge">POST</span> /api/logout
                        </div>

                        <h5 class="mt-4">Products</h5>
                        <div class="api-endpoint">
                            <span class="method-badge" style="background: #28a745;">GET</span> /api/products
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge" style="background: #28a745;">GET</span> /api/products/featured
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge" style="background: #28a745;">GET</span> /api/products/{product}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Cart</h5>
                        <div class="api-endpoint">
                            <span class="method-badge" style="background: #28a745;">GET</span> /api/cart
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge">POST</span> /api/cart/items
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge" style="background: #ffc107;">PUT</span> /api/cart/items/{item}
                        </div>

                        <h5 class="mt-4">Orders</h5>
                        <div class="api-endpoint">
                            <span class="method-badge" style="background: #28a745;">GET</span> /api/orders
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge">POST</span> /api/orders
                        </div>
                        <div class="api-endpoint">
                            <span class="method-badge">POST</span> /api/orders/{order}/cancel
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Base URL</h5>
                    <code>http://localhost:8000/api</code>
                    <br><br>
                    <small class="text-muted">
                        All protected endpoints require authentication using Laravel Sanctum tokens. 
                        Include the token in the Authorization header: <code>Bearer {token}</code>
                    </small>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-store"></i> Laravel Ecommerce</h5>
                    <p>A modern ecommerce platform built with Laravel, featuring a complete API, admin panel, and Docker support.</p>
                </div>
                <div class="col-md-4">
                    <h5>Technologies Used</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Laravel 12</a></li>
                        <li><a href="#">Laravel Sanctum</a></li>
                        <li><a href="#">PostgreSQL</a></li>
                        <li><a href="#">Docker & Docker Compose</a></li>
                        <li><a href="#">Bootstrap 5</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/admin">Admin Panel</a></li>
                        <li><a href="#api">API Documentation</a></li>
                        <li><a href="https://github.com">GitHub Repository</a></li>
                        <li><a href="#">Docker Setup</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p>&copy; 2025 Laravel Ecommerce. Built with ❤️ and lots of ☕</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load featured products
        async function loadProducts() {
            try {
                const response = await fetch('/api/products/featured');
                const data = await response.json();
                const products = data.products || [];
                
                const productsList = document.getElementById('productsList');
                
                if (products.length === 0) {
                    productsList.innerHTML = `
                        <div class="col-12 text-center">
                            <p class="text-muted">No products available. Please seed the database with sample data.</p>
                            <small>Run: <code>php artisan db:seed</code></small>
                        </div>
                    `;
                    return;
                }
                
                productsList.innerHTML = products.map(product => `
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="/images/placeholder.jpg" class="card-img-top" alt="${product.name}">
                            <div class="card-body d-flex flex-column">
                                <span class="category-badge mb-2">${product.category?.name || 'Uncategorized'}</span>
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text flex-grow-1">${product.short_description || ''}</p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="price">$${product.sale_price || product.price}</span>
                                            ${product.sale_price ? `<span class="sale-price">$${product.price}</span>` : ''}
                                        </div>
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading products:', error);
                document.getElementById('productsList').innerHTML = `
                    <div class="col-12 text-center">
                        <p class="text-danger">Error loading products. Please ensure the API is running.</p>
                    </div>
                `;
            }
        }

        // Load products when page loads
        document.addEventListener('DOMContentLoaded', loadProducts);
    </script>
</body>
</html>
