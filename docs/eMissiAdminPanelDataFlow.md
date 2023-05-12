graph TD
    A[E-Missi Platform] --> B[Admin] 
    B --> C(Login)
    C --> S[Revenue] -->X((view))
    C --> T[Workzone Management]
    C --> D[User Management]
    C --> E[Booking Management]
    C --> F[Service Management]
    D --> G(Users)
    D --> H(Service Provider)
    D --> AA(Incomplete User) -->I((view,edit))
    G --> I((view,edit))
    H --> I((view,edit)) 
    E --> J(Available slots) --> N((view))
    E --> L(Bookings) --> N((view))
    E --> M(Booking Reports)--> N((view))
    F --> K(Qualifications)--> R((add,view,edit))
    F --> O(Provider Skills)--> R((add,view,edit))
    F --> P(Services)--> R((add,view,edit))
    F --> Q(Terms)--> R((add,view,edit))
    T --> U(Location) --> W((add,view,edit))
    T --> V(Zone) --> W((aa,view,edit))
    C --> Y[language] -->W((add,view,edit))


