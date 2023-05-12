sequenceDiagram
Admin->>+ServiceProvider:Edit,View,Delete
ServiceProvider->>+Services: Add,Edit ,View
ServiceProvider-->>+Services: services like(nursing, physiotherapy,caregiver etc)
ServiceProvider-->>+Rating:View(rating of services)
Admin->>+User:Edit,View,Delete
User->>+Rating:Add,Edit(Give rating of service)
ServiceProvider->>+Services: Manage Service Slot
User->>+Services:Book Services slot
Services->>+User:Accept Booking
User->>+Services: Payment Online for their booking
Services-->>+User: if payment not recieve in 15 mint automatic cancel booking
ServiceProvider->>+Services: Manage Booking Satus
User->>+ Services: Review the Service
User->>+Communication: User can make call to the booking service
Services->>+Communication: Can manage Calls
Services-->>Admin: if payment is done by user then directly go to admin
Admin->>+ServiceProvider: Settle Payment manually to Service Provider
User->>+Services: Reschedule Booking 
User-->>+Services: Upload Photos(medical report or any other form)
Services->>+User: Upload Photos of User report (if needed)
Admin->>+Services: View Total booking Services and also cancel bookings
ServiceProvider-->>+Admin: When sign-up approval is pending 
Admin->>+ServiceProvider: Accept or Reject Service provider

