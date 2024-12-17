# Bakery Website Style Guide

## Colors

### Primary Colors
- Chocolate Brown (`--bakery-chocolate`): `#3E2723` - Used for headers and primary text
- Butter Yellow (`--bakery-butter`): `#FFE4B5` - Used for accents and highlights
- Flour White (`--bakery-flour`): `#FFF5EE` - Used for backgrounds and light text
- Gold (`--bakery-gold`): `#D4AF37` - Used for special highlights and hover states

### Neutral Colors
- Dark Gray (`--neutral-800`): `#333333` - Used for body text
- Light Gray (`--neutral-200`): `#EEEEEE` - Used for borders and dividers

## Typography

### Fonts
- Headings: 'Pacifico', cursive
- Body: 'Open Sans', sans-serif
- Accents: 'Roboto Slab', serif

### Font Sizes
- H1: 2.5rem
- H2: 2rem
- H3: 1.5rem
- Body: 1rem
- Small: 0.875rem

### Font Weights
- Regular: 400
- Medium: 500
- Bold: 700

## Spacing

### Margins and Padding
- Extra Small: 0.5rem (8px)
- Small: 1rem (16px)
- Medium: 1.5rem (24px)
- Large: 2rem (32px)
- Extra Large: 3rem (48px)

### Grid
- Container Max Width: 1200px
- Grid Columns: 12
- Grid Gap: 2rem (32px)

## Components

### Buttons
```css
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--bakery-chocolate);
    color: var(--bakery-flour);
}

.btn-secondary {
    background: var(--bakery-butter);
    color: var(--bakery-chocolate);
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.125rem;
}
```

### Cards
```css
.recipe-card {
    background: var(--bakery-cream);
    padding: 1.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
```

### Navigation
```css
.navbar {
    background: var(--bakery-chocolate);
    padding: 1rem 2rem;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-links a {
    color: var(--bakery-flour);
    text-decoration: none;
    font-weight: 500;
}
```

## Images

### Recipe Images
- Dimensions: 800x600px
- Format: JPG
- Quality: 80%
- Max File Size: 200KB

### Hero Images
- Dimensions: 1920x1080px
- Format: JPG
- Quality: 90%
- Max File Size: 500KB

## Responsive Breakpoints

- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

## Animations

### Transitions
- Default: 0.3s ease
- Hover Effects: 0.2s ease-in-out
- Page Transitions: 0.4s ease-in-out

### Hover States
- Buttons: Scale 1.05
- Cards: translateY(-5px)
- Links: Color change to var(--bakery-gold)

## Best Practices

1. Maintain consistent spacing using the defined spacing scale
2. Use semantic HTML elements
3. Ensure color contrast meets WCAG 2.1 guidelines
4. Optimize images for web performance
5. Test responsive layouts across all breakpoints
6. Keep animations subtle and purposeful
7. Use CSS variables for maintainable theming
