# 🎨 Bakery Registration Design System

## Typography Scale

### Headers
- H1: Poppins Bold, 48px/56px
- H2: Poppins SemiBold, 36px/44px
- H3: Poppins SemiBold, 24px/32px
- H4: Poppins Medium, 20px/28px
- H5: Poppins Medium, 16px/24px

### Body Text
- Large: Inter Regular, 18px/28px
- Regular: Inter Regular, 16px/24px
- Small: Inter Regular, 14px/20px
- Caption: Inter Regular, 12px/16px

### Button Text
- Primary: Poppins Medium, 16px/24px
- Secondary: Poppins Medium, 14px/20px

## Color System

### Primary Colors
- Primary-900: #2D3436 (Text)
- Primary-800: #355C7D (Deep Blue)
- Primary-700: #F67280 (Coral Pink)
- Primary-600: #F8B195 (Warm Peach)

### Secondary Colors
- Secondary-500: #55efc4 (Mint/Success)
- Secondary-400: #ff7675 (Soft Red/Error)
- Secondary-300: #FFEAA7 (Pale Yellow/Warning)
- Secondary-200: #81ECEC (Sky Blue/Info)

### Neutral Colors
- Neutral-900: #2D3436 (Dark Gray)
- Neutral-800: #636E72 (Gray)
- Neutral-700: #B2BEC3 (Light Gray)
- Neutral-600: #DFE6E9 (Lighter Gray)
- Neutral-500: #F5F6FA (Background Gray)
- Neutral-100: #FFFFFF (White)

## Spacing System
Base unit: 8px

### Margins
- xs: 8px
- sm: 16px
- md: 24px
- lg: 32px
- xl: 40px
- xxl: 48px

### Padding
- xs: 8px
- sm: 16px
- md: 24px
- lg: 32px

## Components

### Buttons
- Height: 48px
- Border Radius: 8px
- States: Default, Hover, Active, Disabled

### Input Fields
- Height: 48px
- Border Radius: 8px
- Border: 1px solid Neutral-700
- States: Default, Focus, Error, Disabled

### Cards
- Border Radius: 12px
- Shadow: 0 2px 4px rgba(0,0,0,0.1)
- Background: Neutral-100

### Form Groups
- Vertical Spacing: 24px
- Label to Input Spacing: 8px

## Grid System
- Columns: 12
- Gutter: 24px
- Margin: 24px
- Breakpoints:
  - Mobile: 320px - 767px
  - Tablet: 768px - 1023px
  - Desktop: 1024px+

## Icons
- Size System:
  - Small: 16x16px
  - Medium: 24x24px
  - Large: 32x32px
- Style: Rounded
- Stroke Width: 2px

## Animations
- Duration: 200ms
- Easing: ease-in-out
- Hover Transitions: 150ms
- Page Transitions: 300ms

## Image Guidelines
- Product Images:
  - Aspect Ratio: 1:1
  - Min Size: 800x800px
  - Format: JPG/PNG
  - Max File Size: 2MB
- Thumbnails:
  - Size: 200x200px
  - Format: WebP
  - Quality: 80%

## Form Validation
### Error States
- Input Border: Secondary-400 (Error Red)
- Error Text: Secondary-400
- Icon: Error icon in Secondary-400
- Background: Neutral-100

### Success States
- Input Border: Secondary-500 (Success Green)
- Success Text: Secondary-500
- Icon: Check icon in Secondary-500
- Background: Neutral-100

## Loading States
- Skeleton Loading:
  - Background: Neutral-600
  - Animation: Pulse
  - Duration: 1.5s
- Spinners:
  - Size: 24px
  - Color: Primary-800
  - Duration: 1s

## Accessibility
- Minimum Text Contrast: 4.5:1
- Focus States: 2px solid Primary-800
- Touch Targets: Minimum 44x44px
- Error Messages: Icon + Text
- Required Fields: Asterisk (*)
