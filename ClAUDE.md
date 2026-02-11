# Project Instructions

This project is a Local POS System for a sweets shop.

The main goals are:
- Simplicity
- Stability
- Ease of use
- Offline operation
- Clear UI
- Keyboard-first workflow

Do not over-engineer solutions.
Always prefer practical and simple implementations.

---

## Language

- Primary language: Arabic (Libyan dialect when appropriate)
- Code: English

---

## General Rules

- Focus on usability and clarity.
- Prioritize speed of daily work.
- Avoid unnecessary complexity.
- Follow the business requirements strictly.
- Do not add features that were not requested.
- Always prioritize real-world usage.

---

## Code Rules

1. When returning code:
   - Always return the FULL file.
   - Never return partial snippets.
   - Never use comments inside code.

2. No inline comments.
3. No block comments.
4. No explanations inside code.
5. Code must be clean and readable by structure only.

---

## Editing Existing Code

When modifying code:

- Always return the full updated file.
- Never return only the changed part.
- Do not remove existing functionality unless asked.

---

## Explanations

- Do NOT explain code unless explicitly asked.
- Provide explanations only in analysis or planning.
- Default behavior: return code only.

---

## Architecture Guidelines

- Modular design
- Each module must be independent
- No tight coupling
- Simple folder structure

---

## Database Design

- Prefer relational structure
- Avoid redundant fields
- Use pivot tables for many-to-many relations
- Keep naming consistent

---

## UI / UX Guidelines

- Large buttons
- Clear typography
- Touch-friendly layout
- Minimal screens
- No clutter
- Fast interaction
- Modal-based forms
- Ajax updates
- No unnecessary reloads

---

## Keyboard-First Rules

The system must be optimized for full keyboard usage.

Mouse usage must be optional, not required.

---

### Mandatory Keyboard Support

All main actions must be accessible via keyboard:

- Open Add Modal
- Save Form
- Cancel Form
- Search
- Delete
- Confirm
- Navigate Tables
- Switch Fields
- Submit Payments

---

### Required Shortcuts (Default)

Use consistent shortcuts across all modules:

- Enter → Confirm / Save
- Esc → Close modal / Cancel
- Ctrl + S → Save
- Ctrl + N → New item
- Ctrl + F → Focus search
- Ctrl + D → Delete selected
- Tab / Shift+Tab → Navigate fields
- Arrow Keys → Navigate lists / tables
- Space → Toggle checkbox / switch

POS & Cashier:

- F1 → Help
- F2 → New Order
- F4 → Print
- F8 → Payment
- F9 → Finish Order

---

### Focus Management

- Auto-focus first input on every screen.
- Auto-focus search fields.
- Auto-focus barcode / scan inputs.
- Return focus after modal close.
- Never leave focus undefined.

No screen should require clicking to start typing.

---

### Form Navigation

- Forms must be fully navigable by keyboard.
- Logical tab order is mandatory.
- No hidden or broken tab paths.
- Enter must move forward or submit.

---

### Table Navigation

All tables must support:

- Arrow navigation
- Row selection by keyboard
- Enter to open edit
- Delete key for delete (with confirmation)

---

## UI Interaction Rules

- Do NOT use native browser confirm() or alert().
- Always use:
  - Modals
  - Toast notifications
  - SweetAlert / SweetAlert2
- All confirmation dialogs must be custom styled.

---

## Select Fields Rules

- All select fields must support search.
- Do NOT use default HTML select for large lists.
- Always use one of the following libraries:
  - Choices.js
  - Select2

- All dropdowns with more than 5 items must be searchable.
- Multi-select fields must always use searchable components.
- Selects must be fully keyboard-navigable.

---

## POS System Rules

- POS screens: No login
- Cashier and Admin: Login required
- Fixed routes per section
- No dynamic device management
- Electron apps load fixed URLs
- Barcode input must always be focused

---

## Financial System Rules

- Safes are independent entities
- Safes can have multiple cashiers
- Payment methods are linked to safes
- All financial operations must be logged
- No deletion of financial records
- Use shifts/closures when required

---

## Development Priorities

1. Products
2. Payment Methods
3. Safes
4. Customers
5. Special Orders
6. Users
7. Permissions
8. Cashier
9. POS

Follow this order strictly.

---

## Error Handling

- Always validate input
- Show clear error messages
- Prevent silent failures
- Log system errors
- Errors must not break keyboard flow

---

## Performance

- Use Ajax for CRUD
- Optimize queries
- Avoid unnecessary reloads
- Cache static data
- No blocking UI actions

---

## Security

- Hash passwords
- Protect routes
- Validate permissions
- Prevent unauthorized access

---

## Communication Style

- Be direct
- Be practical
- Avoid theory
- Focus on implementation

---

## Prohibited

- Over-engineering
- Unnecessary abstractions
- Complex frameworks
- Experimental features
- Cloud dependencies
- Mouse-only workflows

---

## Final Rule

If there is a choice between:
Simple solution vs Complex solution

Always choose the simple solution.

If there is a choice between:
Keyboard workflow vs Mouse workflow

Always choose keyboard workflow.