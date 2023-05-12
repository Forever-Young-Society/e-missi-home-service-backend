graph TD
    A[E-Missi Platform] --> B[User] 
    B --> C(Login)
    C --> D[Services] -->|if slot available|E[Book Services]
    E --> F[Payment]--> |is not done in 15 mints|I[Automatic Cancel Booking]
    D --> K[Review Service]--> E 
    F --> |is done|L[Schedule Booking]
    L --> H[Uploda Medical Report] --> G((add,view,edit))
    H --> |if service done|J[Rating to that service]
    L --> |also reschedule booking|L 
    C --> |add dependent user|M[Dependent User]-->|after adding details like name, location|E