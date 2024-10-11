use gestion_api;
INSERT INTO products (name, description, price, stock, category_id, created_at, updated_at) 
VALUES 
    -- Bebidas
    ('Coca Cola 500ml', 'Bebida gaseosa', 0.60, 100, 1, NOW(), NOW()),
    ('Fanta Naranja 500ml', 'Bebida gaseosa sabor naranja', 0.55, 80, 1, NOW(), NOW()),
    ('Agua Cristal 600ml', 'Agua purificada', 0.50, 120, 1, NOW(), NOW()),
    ('Jugo Del Valle 300ml', 'Jugo de frutas variadas', 0.70, 50, 1, NOW(), NOW()),
    
    -- Alimentos enlatados
    ('Frijoles Ducal', 'Frijoles negros enlatados', 1.25, 60, 2, NOW(), NOW()),
    ('Sardinas en Salsa', 'Sardinas enlatadas con salsa de tomate', 1.50, 40, 2, NOW(), NOW()),
    ('Maíz Dulce Del Monte', 'Maíz dulce enlatado', 1.10, 30, 2, NOW(), NOW()),
    ('Atún Calvo 170g', 'Atún en aceite', 1.80, 70, 2, NOW(), NOW()),
    
    -- Lácteos
    ('Leche Entera La Salud 1L', 'Leche entera', 1.20, 200, 3, NOW(), NOW()),
    ('Yogurt Sabor Fresa 200ml', 'Yogurt con sabor a fresa', 0.80, 100, 3, NOW(), NOW()),
    ('Mantequilla Lido 200g', 'Mantequilla tradicional', 1.50, 50, 3, NOW(), NOW()),
    ('Queso Fresco 1lb', 'Queso fresco artesanal', 2.50, 40, 3, NOW(), NOW()),
    
    -- Panadería y Tortillería
    ('Pan Frances', 'Pan de corteza dura', 0.15, 500, 4, NOW(), NOW()),
    ('Tortillas de Maíz', 'Tortillas de maíz tradicionales', 0.10, 300, 4, NOW(), NOW()),
    ('Pan Dulce', 'Pan dulce típico', 0.25, 250, 4, NOW(), NOW()),
    ('Baguette', 'Pan al estilo francés', 0.75, 80, 4, NOW(), NOW()),
    
    -- Snacks
    ('Doritos 145g', 'Chips de tortilla sabor nacho', 1.00, 100, 5, NOW(), NOW()),
    ('Churritos Diana', 'Churritos de maíz con sabor a limón', 0.25, 200, 5, NOW(), NOW()),
    ('Manía Diana 40g', 'Manía salada', 0.35, 150, 5, NOW(), NOW()),
    ('Palitos de Queso Diana', 'Palitos de queso fritos', 0.30, 180, 5, NOW(), NOW()),
    
    -- Productos de limpieza
    ('Jabón Líquido Fab 1L', 'Detergente líquido para ropa', 3.50, 90, 6, NOW(), NOW()),
    ('Cloro Magia Blanca 1L', 'Blanqueador desinfectante', 1.25, 120, 6, NOW(), NOW()),
    ('Esponjas Scotch-Brite', 'Esponjas multiusos para limpieza', 1.10, 70, 6, NOW(), NOW()),
    ('Desinfectante Pinol 1L', 'Desinfectante para pisos y superficies', 2.00, 85, 6, NOW(), NOW()),
    
    -- Higiene personal
    ('Pasta Dental Colgate 90g', 'Pasta dental con flúor', 1.25, 150, 7, NOW(), NOW()),
    ('Jabón de Baño Dove 135g', 'Jabón en barra humectante', 1.50, 100, 7, NOW(), NOW()),
    ('Shampoo Head & Shoulders 400ml', 'Shampoo anticaspa', 3.75, 80, 7, NOW(), NOW()),
    ('Crema Corporal Nivea 200ml', 'Crema hidratante para el cuerpo', 3.00, 60, 7, NOW(), NOW()),
    
    -- Carnes frías y embutidos
    ('Salchicha Toledo 250g', 'Salchichas de cerdo', 1.50, 90, 8, NOW(), NOW()),
    ('Chorizo Criollo 1lb', 'Chorizo artesanal', 2.50, 60, 8, NOW(), NOW()),
    ('Mortadela Pollo Toledo 500g', 'Mortadela de pollo', 2.20, 70, 8, NOW(), NOW()),
    ('Jamonada Sula 400g', 'Jamonada de cerdo', 1.90, 50, 8, NOW(), NOW()),
    
    -- Granos y semillas
    ('Frijol Rojo 1lb', 'Frijol rojo salvadoreño', 1.00, 300, 9, NOW(), NOW()),
    ('Arroz San Pedro 1lb', 'Arroz blanco premium', 0.90, 400, 9, NOW(), NOW()),
    ('Lentejas 500g', 'Lentejas secas', 0.85, 250, 9, NOW(), NOW()),
    ('Maíz Blanco 1lb', 'Maíz blanco', 0.75, 200, 9, NOW(), NOW()),
    
    -- Dulcería
    ('Chocolate Tosh 40g', 'Barra de chocolate con cereal', 0.50, 150, 10, NOW(), NOW()),
    ('Chocolates Hershey 40g', 'Barra de chocolate con leche', 0.60, 140, 10, NOW(), NOW()),
    ('Gomitas Yumy', 'Gomitas de frutas', 0.30, 200, 10, NOW(), NOW()),
    ('Paletas Tutsi Pop', 'Paletas rellenas de chicle', 0.15, 300, 10, NOW(), NOW()),
    
    -- Frutas y verduras
    ('Plátano', 'Plátano maduro', 0.20, 500, 11, NOW(), NOW()),
    ('Manzana Roja', 'Manzana roja importada', 0.75, 100, 11, NOW(), NOW()),
    ('Tomate', 'Tomate fresco', 0.50, 200, 11, NOW(), NOW()),
    ('Lechuga Romana', 'Lechuga fresca', 0.80, 150, 11, NOW(), NOW()),
    
    -- Cereales y harinas
    ('Harina de Trigo 1kg', 'Harina de trigo para panadería', 1.10, 80, 12, NOW(), NOW()),
    ('Corn Flakes 500g', 'Cereal de hojuelas de maíz', 2.50, 60, 12, NOW(), NOW()),
    ('Avena Quaker 400g', 'Avena en hojuelas', 1.50, 100, 12, NOW(), NOW()),
    ('Harina de Maíz Maseca 1kg', 'Harina para tortillas', 1.20, 120, 12, NOW(), NOW()),
    
    -- Especias y condimentos
    ('Sal Refinada 1kg', 'Sal fina para consumo diario', 0.45, 150, 13, NOW(), NOW()),
    ('Pimienta Negra Molida 100g', 'Pimienta negra en polvo', 1.75, 70, 13, NOW(), NOW()),
    ('Ajo en Polvo 100g', 'Ajo deshidratado en polvo', 1.20, 80, 13, NOW(), NOW()),
    ('Comino Molido 50g', 'Comino en polvo', 0.90, 100, 13, NOW(), NOW()),
    
    -- Aceites y vinagres
    ('Aceite Ideal 1L', 'Aceite vegetal', 2.25, 200, 14, NOW(), NOW()),
    ('Aceite Oliva Virgen 500ml', 'Aceite de oliva extra virgen', 4.50, 50, 14, NOW(), NOW()),
    ('Vinagre Blanco 500ml', 'Vinagre para cocina', 0.75, 150, 14, NOW(), NOW()),
    ('Vinagre de Manzana 500ml', 'Vinagre para ensaladas', 1.00, 120, 14, NOW(), NOW()),
    
    -- Congelados
    ('Pollo Entero Congelado 1kg', 'Pollo entero congelado', 3.50, 80, 15, NOW(), NOW()),
    ('Papas Fritas Congeladas 500g', 'Papas fritas precocidas', 1.90, 90, 15, NOW(), NOW()),
    ('Nuggets de Pollo 500g', 'Nuggets de pollo congelados', 3.20, 70, 15, NOW(), NOW()),
    ('Helado de Vainilla 1L', 'Helado sabor vainilla', 2.50, 50, 15, NOW(), NOW());
