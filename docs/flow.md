# Reestructuración de Productos y Variantes

Esta documentación describe el nuevo sistema de variantes dinámicas implementado para Kuchas Kids.

## Esquema de Base de Datos (ER)

```mermaid
erDiagram
    PRODUCTS ||--o{ PRODUCT_VARIANTS : "tiene"
    ATTRIBUTES ||--o{ ATTRIBUTE_OPTIONS : "define"
    PRODUCT_VARIANTS }o--o{ ATTRIBUTE_OPTIONS : "se compone de"
    
    PRODUCTS {
        bigint id
        string name
        string slug
        text description
    }

    PRODUCT_VARIANTS {
        bigint id
        bigint product_id
        string sku
        decimal price
        decimal offer_price
        int stock
        boolean status
    }

    ATTRIBUTES {
        bigint id
        string name
    }

    ATTRIBUTE_OPTIONS {
        bigint id
        bigint attribute_id
        string value
        string hex
    }
```

## Flujo de Gestión (CRUD)

El siguiente diagrama describe cómo se gestionan los productos y sus variantes en el panel administrativo:

```mermaid
graph TD
    A[Inicio: Crear/Editar Producto] --> B[Definir Datos Base: Nombre, Categoria, Marca]
    B --> C{¿Tiene Variantes?}
    C -- No --> D[Producto Simple: Definir Stock/Precio en tabla base]
    C -- Sí --> E[Seleccionar Atributos: Color, Talla, etc.]
    E --> F[Seleccionar Opciones para cada Atributo]
    F --> G[Generar Matriz de Combinaciones]
    G --> H[Asignar SKU, Stock y Precio a cada Variante]
    H --> I[Guardar Producto y Variantes]
    
    subgraph "Configuración Previa"
        P1[Configurar Atributos Globales] --> P2[Configurar Opciones por Atributo]
    end
    
    P2 -.-> E
```

### Notas sobre la Implementación
1. **Flexibilidad:** El sistema permite añadir cualquier tipo de atributo (Material, Tipo de Cuello, etc.) sin cambiar la base de datos.
2. **Generación Automática:** Al seleccionar 2 colores y 3 tallas, el sistema Livewire generará automáticamente 6 filas de variantes.
3. **Escalabilidad:** Las variantes se guardan en su propia tabla, lo que facilita el control de stock preciso por cada combinación.
