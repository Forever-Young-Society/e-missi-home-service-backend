graph TD
    A[E-Missi Platform] --> B[Service Provider] 
    B --> C(Signup)
    C --> |if approval by admin|D[Login]
    D --> E(Service Management)
    E --> F[Services] -->K[Provider skills]-->L((add,edit,view))
    F --> M[Qualifications]--> N((add,edit,view)) 
    E --> G[Accept Bookings]
    E --> H[Cancel Booking]
    G --> I[Service]-->|if service is completed|J[Upload Medical Report]
    E --> O[Workzone]
    O --> |add,edit,view|P[Zone]
    O --> |add,edit,view|Q[location]