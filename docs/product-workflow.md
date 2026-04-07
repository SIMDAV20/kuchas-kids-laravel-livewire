# Flujo de Creación y Edición de Productos con Variantes

Este documento describe el ciclo completo de vida administrativo para la gestión de productos y sus variantes dinámicas, actualizado tras la reestructuración del backend.

## 1. Gestión de Atributos Globales
Antes de crear variantes, el administrador debe definir las características disponibles en la tienda.

1. Navegar a **Menú > Atributos**.
2. **Crear Atributo:** Definir la característica principal (ej., "Color", "Talla", "Tela").
3. **Añadir Opciones:** Asignar valores específicos a cada atributo.
    * *Ejemplo Talla:* S, M, L, XL.
    * *Ejemplo Color:* Rojo, Azul, Verde (Al detectar que el atributo es "Color", el sistema permite seleccionar un código HEX para renderizar un selector visual).
4. Estos datos se almacenan en las tablas `attributes` y `attribute_options`.

## 2. Creación del Producto Base (CreateProduct)
La creación inicial de un producto no contempla variantes en el primer paso, simplificando el flujo.

1. Navegar a **Menú > Productos > Crear**.
2. Seleccionar Categoría y Subcategoría (las Marcas se filtran automáticamente basadas en la categoría).
3. Completar datos base: Nombre, Descripción, Precio referencial, Stock genérico (si aplica).
4. El sistema autogenera un `slug` único.
5. Al hacer clic en "Crear Producto", el registro se guarda en la tabla `products`.
6. **Redirección:** El sistema redirige automáticamente a la pantalla de Edición Avanzada para continuar configurando galerías y variantes.

## 3. Edición Avanzada y Generación de Variantes (EditProduct)
Esta es la interfaz principal donde sucede la magia de la personalización de productos.

### 3.1. Selección de Atributos del Producto
1. En la sección "Gestión de Variantes", el sistema muestra todos los Atributos globales disponibles (creados en el Paso 1).
2. El administrador **marca los checkboxes** interactivos de las opciones que este producto en particular ofrece.
    * *Ejemplo:* Si el producto es un "Polo Kids", marcará las opciones "Rojo", "Azul" del atributo *Color* y las opciones "4", "6", "8" del atributo *Talla*.

### 3.2. Generación del Producto Cartesiano
1. Al pulsar el botón **"Generar Variantes"**.
2. El backend procesa las opciones y calcula todas las combinaciones posibles matemáticamente (Producto Cartesiano).
    * *Ejemplo (2 colores x 3 tallas = 6 variantes).*
3. El sistema autogenera un **SKU/Slug único** por cada combinación. Ejemplo: `polo-kids-rojo-4`.
4. Se crean automáticamente los registros en la tabla unificada `product_variants`.

### 3.3. Edición en Línea de Variantes
1. Las variantes generadas aparecen en una tabla reactiva en la parte inferior.
2. El administrador puede **editar directamente el SKU, Precio y Stock** de cada variante. Estos campos reaccionan y guardan los datos en servidor en tiempo real (gracias al event listener en Livewire).
3. Se puede eliminar cualquier variante específica con el botón de papelera.

## 4. Gestión de la Galería de Imágenes
Con el fin de evitar la duplicación excesiva en la carga de archivos, la gestión de imágenes se separa en dos pasos simples:

### 4.1. Galería Maestra del Producto
1. En la parte inferior de la pantalla de edición se encuentra el módulo "Galería del Producto".
2. Se suben en bloque (Dropzone) todas las fotografías disponibles de ese producto, sin importar la variante.
3. Estas imágenes se enlazan polimórficamente a la tabla `products` mediante el modelo `Image` (`imageable_type = 'App\Models\Product'`).

### 4.2. Asignación Individual a Variantes
1. Regresando a la tabla de variantes generadas, cada fila posee un botón **"Asignar"** en la columna "Imágenes".
2. Pulsar este botón abre un **Modal de Selección** mostrando la Galería Maestra del producto.
3. El administrador selecciona las fotografías pertenecientes a esa variante específica (marcando con un *check* azul).
4. Al "Guardar Asignación", el sistema guarda un Array con los IDs de las imágenes seleccionadas dentro de la columna JSON `images` de la tabla `product_variants`.
5. La vista de la tabla ahora muestra un *preview* (miniaturas) de las imágenes asignadas a cada variante.

## 5. Frontend & Selección de Variantes
Al culminar la configuración administrativa, los datos están optimizados para el Frontend:

### 5.1. Selección Reactiva (ProductDetail)
- **Componente:** El sistema frontend de detalles (`ProductDetail`) recibe directamente del backend qué colores y tallas existen.
- **Filtrado Inteligente:** Al seleccionar un Color, las Tallas se filtran automáticamente basándose en las variantes que realmente tienen stock.
- **Galería Dinámica:** Si hay imágenes asignadas en el JSON de la variante seleccionada, el slider de producto cambia a esas fotos; si no, muestra la galería general.
- **Precio & Stock:** El precio se actualiza al instante, priorizando: 
    1. Oferta Flash (Variante) > 2. Oferta Flash (Producto) > 3. Oferta Normal > 4. Precio Base.

### 5.2. Proceso de Compra
Una vez seleccionados los atributos, el control pasa al componente unificado de carrito. 

Para más detalles sobre este flujo, consulte: **[docs/cart-workflow.md](cart-workflow.md)**.
