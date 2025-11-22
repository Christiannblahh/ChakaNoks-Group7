Core System Features Implementation Plan

The following tracked tasks map to the project requirements (inventory, purchasing, logistics, dashboard, franchising, security, and integrations). These are recorded in the project's TODO list for implementation and review.

1. Define project scope
- Confirm platforms (web + mobile-compatible), DB choice (MySQL/Postgres), integrations (barcode, SMS/email), and security constraints (SSL, RBAC, backups). Update environment and configs.

2. Design DB schema
- Branches, inventory_items, stock_levels (per branch), suppliers, purchase_orders, order_items, deliveries, transfers, franchises, users, roles, permissions, activity_logs, and backup metadata. Create initial migration files.

3. Implement RBAC
- Roles (Branch Manager, Inventory Staff, Central Admin, Supplier, Logistics Coordinator, Franchise Manager, System Admin) and permission checks in controllers/middleware.

4. Inventory module
- Real-time inventory per branch, stock adjustments, barcode scanning endpoints, perishable expiry tracking, and low-stock alert generator.

5. Purchase & supplier module
- Centralized supplier DB, purchase request flow (Branch → Central → Supplier), automated purchase generation, supplier performance metrics, order tracking statuses.

6. Logistics & distribution
- Delivery scheduling, route optimization stub/integration, delivery tracking, and inter-branch transfer requests.

7. Central dashboard
- Consolidated branch reporting, approval management, analytics (cost, wastage, demand), and export options.

8. Franchising features
- Application processing workflow, supply allocation, royalty/payment tracking, and franchise-specific reports.

9. Integrations & alerts
- Barcode scanner integration, SMS/email alerts for low-stock and approvals, and external route optimization APIs.

10. Backup & recovery
- Scheduled DB backups, verification, and recovery documentation for System Admin.

11. Testing & CI
- Unit and integration tests (`phpunit`), CI pipeline, and test coverage for inventory/purchase workflows and RBAC enforcement.

12. Documentation & deployment
- README, deployment steps for Windows/XAMPP and Linux, environment configuration, and demo seeders.

Next steps
- I can scaffold the initial DB migration and the Inventory module (models, controllers, and basic views) tailored to the CodeIgniter structure present in this repository.
- Please confirm which DB you prefer (MySQL or PostgreSQL) and whether to proceed with scaffolding now.
