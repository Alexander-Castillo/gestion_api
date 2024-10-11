use gestion_api;
INSERT INTO inventory_transactions (user_id, product_id, quantity, transaction_type, created_at, updated_at) 
VALUES 
    (1, 1, 50, 'entradas', NOW(), NOW()),   -- Usuario 1 agrega 50 unidades al producto 1
    (2, 2, 20, 'salidas', NOW(), NOW()),    -- Usuario 2 retira 20 unidades del producto 2
    (3, 3, 30, 'entradas', NOW(), NOW()),   -- Usuario 3 agrega 30 unidades al producto 3
    (1, 4, 10, 'salidas', NOW(), NOW()),    -- Usuario 1 retira 10 unidades del producto 4
    (2, 5, 25, 'entradas', NOW(), NOW());   -- Usuario 2 agrega 25 unidades al producto 5
