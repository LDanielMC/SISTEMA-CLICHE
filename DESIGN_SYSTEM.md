# 🎨 Sistema de Diseño Cliché SGI
## Guía Visual Completa - Estética Acuarela Pastel

---

## 📐 TOKENS DE DISEÑO

### 🎨 PALETA DE COLORES

#### Colores Principales (Pastel con transparencia)
```
Azul Cliché Principal:
- #0149a8 (solo para logo y elementos críticos de marca)
- #4A90E2 / rgba(74, 144, 226, 0.15) - Azul pastel suave
- #6BA3E8 / rgba(107, 163, 232, 0.12) - Azul cielo pastel

Tonos Acuarela (Fondos y overlays):
- Sky: #E0F2FE / rgba(224, 242, 254, 0.6)
- Lavanda: #EDE9FE / rgba(237, 233, 254, 0.5)
- Rosa suave: #FCE7F3 / rgba(252, 231, 243, 0.5)
- Menta: #D1FAE5 / rgba(209, 250, 229, 0.5)
- Melocotón: #FEF3C7 / rgba(254, 243, 199, 0.5)
```

#### Colores Funcionales (Pastel)
```
Éxito:
- Verde menta: #86EFAC / rgba(134, 239, 172, 0.3)
- Verde suave: #BBF7D0 / rgba(187, 247, 208, 0.4)

Advertencia:
- Amarillo pastel: #FDE68A / rgba(253, 230, 138, 0.3)
- Ámbar suave: #FCD34D / rgba(252, 211, 77, 0.3)

Error:
- Rosa coral: #FCA5A5 / rgba(252, 165, 165, 0.3)
- Rojo suave: #FEB2B2 / rgba(254, 178, 178, 0.3)

Info:
- Azul cielo: #93C5FD / rgba(147, 197, 253, 0.3)
- Cyan pastel: #A5F3FC / rgba(165, 243, 252, 0.3)
```

#### Colores de Módulos (Identificadores sutiles)
```
Empleados: #DBEAFE (Azul cielo muy suave)
Clientes: #E0E7FF (Índigo pastel)
Tareas: #FFEDD5 (Naranja pastel)
Cotizaciones: #D1FAE5 (Verde menta)
Minutas: #CFFAFE (Cyan pastel)
Suscripciones: #F3E8FF (Morado pastel)
Eventos: #D1FAE5 (Verde agua)
Calendario: #FCE7F3 (Rosa pastel)
```

#### Grises (Texto y bordes)
```
Texto principal: #1F2937 (gray-800)
Texto secundario: #6B7280 (gray-500)
Texto terciario: #9CA3AF (gray-400)
Bordes: #E5E7EB / rgba(229, 231, 235, 0.6) (gray-200 con alpha)
Fondos: #F9FAFB / rgba(249, 250, 251, 0.8) (gray-50 con alpha)
```

---

### 📝 TIPOGRAFÍA

```
Familia: Inter, system-ui, sans-serif

Tamaños:
- Display: 2.5rem (40px) - font-black
- H1: 2rem (32px) - font-bold
- H2: 1.5rem (24px) - font-semibold
- H3: 1.25rem (20px) - font-semibold
- H4: 1.125rem (18px) - font-medium
- Body: 1rem (16px) - font-normal
- Small: 0.875rem (14px) - font-normal
- Tiny: 0.75rem (12px) - font-medium

Pesos:
- Normal: 400
- Medium: 500
- Semibold: 600
- Bold: 700
- Black: 900 (solo para títulos especiales)

Interlineado:
- Tight: 1.25
- Normal: 1.5
- Relaxed: 1.75
```

---

### 🔲 RADIOS (Bordes redondeados)

```
- xs: 0.25rem (4px) - badges pequeños
- sm: 0.375rem (6px) - inputs, botones pequeños
- md: 0.5rem (8px) - botones, cards pequeñas
- lg: 0.75rem (12px) - cards, modales
- xl: 1rem (16px) - cards destacadas
- 2xl: 1.5rem (24px) - elementos hero
- full: 9999px - pills, avatares
```

---

### 🌫️ SOMBRAS (Muy sutiles)

```
- Ninguna: none
- Suave: 0 1px 2px 0 rgba(0, 0, 0, 0.03)
- Normal: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05)
- Media: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -2px rgba(0, 0, 0, 0.06)
- Grande: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.08)
- XL: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.08)

Sombras de color (acuarela):
- Azul: 0 4px 12px rgba(74, 144, 226, 0.15)
- Morado: 0 4px 12px rgba(139, 92, 246, 0.12)
- Rosa: 0 4px 12px rgba(236, 72, 153, 0.12)
- Verde: 0 4px 12px rgba(34, 197, 94, 0.12)
```

---

### 📏 ESPACIADOS

```
Sistema de 4px:
- 0: 0
- 1: 0.25rem (4px)
- 2: 0.5rem (8px)
- 3: 0.75rem (12px)
- 4: 1rem (16px)
- 5: 1.25rem (20px)
- 6: 1.5rem (24px)
- 8: 2rem (32px)
- 10: 2.5rem (40px)
- 12: 3rem (48px)
- 16: 4rem (64px)
- 20: 5rem (80px)

Uso recomendado:
- Padding interno cards: p-6 (24px)
- Gap entre elementos: gap-4 (16px)
- Margen entre secciones: mb-8 (32px)
- Padding contenedores: px-4 sm:px-6 lg:px-8
```

---

## 🧩 COMPONENTES

### Botones

#### Primario (Acción principal)
```css
bg-gradient-to-r from-blue-400/80 to-indigo-400/80
hover:from-blue-500/90 hover:to-indigo-500/90
text-white font-semibold
px-6 py-2.5 rounded-lg
shadow-md hover:shadow-lg
transition-all duration-200
border border-blue-300/30
```

#### Secundario
```css
bg-white/80 backdrop-blur-sm
hover:bg-white/95
text-gray-700 font-medium
px-6 py-2.5 rounded-lg
shadow-sm hover:shadow-md
transition-all duration-200
border border-gray-200/60
```

#### Terciario (Ghost)
```css
bg-transparent
hover:bg-gray-100/50
text-gray-600 font-medium
px-4 py-2 rounded-lg
transition-all duration-200
```

#### Peligro
```css
bg-gradient-to-r from-red-300/70 to-pink-300/70
hover:from-red-400/80 hover:to-pink-400/80
text-red-900 font-semibold
px-6 py-2.5 rounded-lg
shadow-md hover:shadow-lg
transition-all duration-200
border border-red-200/40
```

---

### Cards

#### Card Estándar
```css
bg-white/70 backdrop-blur-md
rounded-2xl
shadow-sm hover:shadow-md
border border-gray-200/50
p-6
transition-all duration-200
```

#### Card con Acuarela
```css
bg-white/60 backdrop-blur-sm
rounded-2xl
shadow-lg
border border-white/60
overflow-hidden
position: relative

/* Fondo acuarela */
&::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 20% 30%, rgba(147, 197, 253, 0.15), transparent 50%),
              radial-gradient(circle at 80% 70%, rgba(196, 181, 253, 0.12), transparent 50%);
  z-index: -1;
}
```

---

### Inputs

```css
bg-white/80 backdrop-blur-sm
border border-gray-200/60
rounded-lg
px-4 py-2.5
text-gray-900
placeholder:text-gray-400
focus:ring-2 focus:ring-blue-300/30
focus:border-blue-400/50
transition-all duration-200
shadow-sm
```

---

### Badges

#### Estado Activo
```css
bg-green-100/60 backdrop-blur-sm
text-green-700
px-3 py-1 rounded-full
text-xs font-medium
border border-green-200/40
```

#### Estado Inactivo
```css
bg-gray-100/60 backdrop-blur-sm
text-gray-600
px-3 py-1 rounded-full
text-xs font-medium
border border-gray-200/40
```

---

### Tablas

```css
/* Contenedor */
overflow-x-auto
border border-gray-200/50 rounded-xl
shadow-sm

/* Tabla */
min-w-full divide-y divide-gray-200/50

/* Header */
bg-gradient-to-r from-gray-50/80 to-blue-50/60 backdrop-blur-sm
text-xs font-semibold text-gray-700 uppercase tracking-wider
px-6 py-4

/* Filas */
bg-white/50 backdrop-blur-sm
hover:bg-blue-50/30
transition-colors duration-150
border-b border-gray-100/50

/* Celdas */
px-6 py-4 text-sm text-gray-900
```

---

### Alerts

#### Éxito
```css
bg-green-50/60 backdrop-blur-sm
border-l-4 border-green-400/60
text-green-700
p-4 rounded-lg
shadow-sm
```

#### Error
```css
bg-red-50/60 backdrop-blur-sm
border-l-4 border-red-400/60
text-red-700
p-4 rounded-lg
shadow-sm
```

#### Info
```css
bg-blue-50/60 backdrop-blur-sm
border-l-4 border-blue-400/60
text-blue-700
p-4 rounded-lg
shadow-sm
```

---

## 🌊 EFECTOS ACUARELA

### Fondos de Página

```css
/* Fondo base */
background: linear-gradient(135deg, #F9FAFB 0%, #EFF6FF 100%);
min-height: 100vh;

/* Blobs acuarela */
position: relative;
&::before {
  content: '';
  position: absolute;
  top: -10%;
  left: -5%;
  width: 30rem;
  height: 30rem;
  background: radial-gradient(circle, rgba(147, 197, 253, 0.2), transparent 70%);
  border-radius: 50%;
  filter: blur(60px);
  z-index: -1;
}

&::after {
  content: '';
  position: absolute;
  bottom: -10%;
  right: -5%;
  width: 35rem;
  height: 35rem;
  background: radial-gradient(circle, rgba(196, 181, 253, 0.15), transparent 70%);
  border-radius: 50%;
  filter: blur(70px);
  z-index: -1;
}
```

### Navbar con Acuarela

```css
background: rgba(255, 255, 255, 0.7);
backdrop-filter: blur(12px);
border-bottom: 1px solid rgba(229, 231, 235, 0.6);
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);

/* Blobs sutiles */
position: relative;
&::before {
  content: '';
  position: absolute;
  top: -20px;
  left: 10%;
  width: 15rem;
  height: 15rem;
  background: radial-gradient(circle, rgba(224, 242, 254, 0.4), transparent 60%);
  border-radius: 50%;
  filter: blur(40px);
  z-index: -1;
}
```

---

## ✅ CHECKLIST DE CONSISTENCIA

### Antes de implementar cualquier vista:
- [ ] Usa solo colores de la paleta pastel definida
- [ ] Aplica backdrop-blur en elementos con transparencia
- [ ] Usa los radios definidos (lg para cards, md para botones)
- [ ] Sombras sutiles (shadow-sm o shadow-md máximo)
- [ ] Espaciados consistentes (sistema de 4px)
- [ ] Tipografía con pesos correctos
- [ ] Transiciones suaves (duration-200)
- [ ] Fondos con blobs acuarela donde aplique
- [ ] Bordes con transparencia (alpha 0.5-0.6)
- [ ] Hover states sutiles y coherentes

### Validación final:
- [ ] Todas las vistas se sienten del mismo sistema
- [ ] No hay colores saturados o neón
- [ ] Texto legible con buen contraste
- [ ] Efectos acuarela presentes pero no abrumadores
- [ ] Transiciones y animaciones suaves
- [ ] Responsive y funcional en todos los tamaños

---

## 🎯 PRINCIPIOS CLAVE

1. **Suavidad sobre contraste**: Preferir transiciones suaves y colores pastel
2. **Transparencia inteligente**: Usar alpha y backdrop-blur para profundidad
3. **Consistencia absoluta**: Mismo estilo en todos los módulos
4. **Acuarela sutil**: Efectos presentes pero no dominantes
5. **Legibilidad primero**: Nunca sacrificar legibilidad por estética
6. **Espacios respirables**: Usar padding y gap generosos
7. **Animaciones sutiles**: Transiciones de 200ms, sin efectos bruscos
8. **Jerarquía clara**: Títulos, subtítulos y texto bien diferenciados

---

**Versión:** 1.0  
**Fecha:** Enero 2026  
**Proyecto:** Cliché SGI
