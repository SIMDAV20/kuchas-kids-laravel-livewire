# Flujo del Carrito de Compras (Cart Workflow)

Este documento describe el ciclo de vida de un producto desde que el cliente lo selecciona hasta que se prepara para la orden, utilizando el sistema unificado de variantes.

## 1. Selección y Adición (AddCartItem)

El componente `AddCartItem` es el único punto de entrada al carrito, diseñado para manejar tanto productos simples como variables.

1. **Detección de Variantes:** El componente recibe un `variantId`. Si es nulo, trata al producto como un item simple (base).
2. **Validación de Stock Real:** Consulta la función `qty_available($productId, $variantId)` que:
    - Obtiene el stock físico de la DB (`product_variants.stock` o `products.quantity`).
    - Resta la cantidad que el usuario ya tiene en su sesión actual de carrito.
3. **Persistencia en Sesión:** Al hacer clic en "Añadir a la bolsa", se guarda en el carrito (Shoppingcart):
    - **ID:** ID del producto base.
    - **Name:** Nombre del producto.
    - **Price:** Precio calculado por `applyOffer()`.
    - **Options:** 
        - `variant_id`: ID de la variante (si aplica).
        - `image`: URL de la imagen (de la variante o del producto).
        - `base_price`: Precio original sin oferta (para mostrar ahorros).
        - **Atributos:** Se guardan dinámicamente todos los pares clave/valor (ej: "Color" => "Rojo", "Talla" => "M").

## 2. Gestión en el Carrito (ShoppingCart & Dropdown)

Los componentes de visualización del carrito son completamente agnósticos al tipo de producto:

1. **Renderizado Dinámico:** Recorren el array `options` del item. Si encuentran claves que no sean internas (`image`, `variant_id`, etc.), las renderizan como etiquetas de atributos.
2. **Actualización de Cantidades (`UpdateCartItem`):**
    - Al incrementar (+) o decrementar (-), el componente vuelve a validar el stock disponible usando el `variant_id` guardado en las opciones.
    - Emite eventos globales para sincronizar el contador del menú y el dropdown lateral.

## 3. Finalización de Compra (Checkout & Stock)

Cuando el usuario confirma la orden en `CreateOrder`:

1. **Creación de Orden:** El contenido del carrito se serializa en la columna `content` de la tabla `orders`.
2. **Descuento de Inventario (`discount`):**
    - El sistema recorre los items del carrito.
    - Si el item tiene `variant_id`, descuenta el stock de la tabla `product_variants`.
    - Si no tiene, lo descuenta de la tabla `products`.
3. **Limpieza:** Se destruye la sesión del carrito tras redirigir a la pasarela de pago.

## 4. Funciones de Soporte (Helpers)

El flujo depende de funciones globales en `app/helpers.php`:
- `current_quantity()`: Retorna el stock físico actual.
- `qty_added()`: Retorna cuánto hay de ese item específico en el carrito actual.
- `qty_available()`: La diferencia entre lo físico y lo reservado en sesión.
- `applyOffer()`: Calcula el precio final considerando Ofertas Flash (prioridad) y Ofertas Normales.
