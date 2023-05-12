classDiagram
    contact_phone <|-- user

    user : +int id
    user : +strinig full_name
    user : +int designation
    user : +string email
    user : +String password
    user : +date date_of_birth
    user : +int gender
    user : +text about_me
    user : +string contact_no
    user : +string address
    user : +string latitude
    user : +string longitude
    user : +string city
    user : +string country
    user : +string zipcode
    user : +string language
    user : +string profile_file
    user : +string experience
    user : +int tos
    user : +int role_id
    user : +int state_id
    user : +int type_id
    user : +datetime last_visit_time 
    user : +datetime last_action_time
    user : +datetime last_password_change
    user : +int login_error_count
    user : +string activation_key
    user : +string timezone
    user : +datetime created_on
    user : +datetime updated_on
    user : +int created_by_id
    user : +int email_verified
    user : +int push_enabled
    user : +int email_enabled 
    
     contact_address <|-- user
 class contact_address{
      +int id
      +String title
      +string  address
      +string  email
      +string  tel
      +string  mobile
      +string latitude
      +string longitude
      +string country
      +int state_id
      +string image_file
      +string type_id
      +datetime created_on
      +datetime updated_on
      +int created_by_id
     
    }
 contact_phone <|-- contact_address

    class contact_phone{
      +int id
      +String title
      +string  contact_no
      +string  type_chat
      +string  skype_chat
      +string  gtalk_chat
      +int type_id
      +int state_id
      +int whatsapp_enable
      +int telegram_enable
      +int toll_fee_enable
      +string country
      +datetime created_on
      +int created_by_id
     
    }

    availability_slot <|-- user

    class availability_slot{
        +int id
      +datetime time
      +datetime time
      +int slot_gap_time
      +int type_id
      +int state_id
      +datetime created_on
      +int created_by_id 
    }


     availability_provider_slot <|-- user
     availability_provider_slot <|-- availability_slot
    class availability_provider_slot{
        +int id
      +datetime start_time
      +datetime end_time
      +int availability_slot_id
      +int type_id
      +int state_id
      +datetime created_on
      +int created_by_id 
    }


    availability_slot_booking <|-- user
    availability_slot_booking <|-- availability_slot
    class availability_slot_booking{
      +int id
      +datetime start_time
      +datetime end_time
      +int provider_id
      +int service_id
      +int dependant_id
      +int workzone_id
      +string order_id
      +string zipcode
      +string address
      +string cancel_reason
      +int cancel_date
      +string slot_id
      +string transaction_id
      +text description
      +int provider_reschedule
      +string user_amount
      +string provider_amount
      +string admin_revenue
      +int user_reschedule
      +int is_reschedule_confirm
      +int payment_status
      +datetime old_start_time
      +datetime old_end_time
      +int type_id
      +int state_id
      +datetime created_on
      +int created_by_id 
    }


    availability_booking_service <|-- user
    availability_booking_service <|-- availability_slot_booking
    class availability_booking_service{
        +int id
        +string title
        +int booking_id
        +int service_id
        +int state_id
        +int type_id
        +datetime created_on
        +int created_by_id
    }


    rating <|-- user
     rating<|-- availability_booking_service
     class rating{
        +int id
        +int model_id
        +string model_type
        +int provider_id
        +float rating
        +string title
        +text comment
        +int state_id
        +int type_id
        +datetime created_on
        +datetime updated_on
        +int created_by_id
    }

    service_skill <|-- user
    class service_skill{
         +int id
        +string title
        +string image_file
        +int category_id 
        +int parent_id 
        +int state_id
        +int type_id
        +datetime created_on
        +int created_by_id 
    }


     user_skill <|-- user
     user_skill <|-- service_skill
    class user_skill{
         +int id
        +string title
        +int category_id 
        +int skill_id 
        +int parent_skill_id 
        +int state_id
        +int type_id
        +datetime created_on
        +int created_by_id 
    }

     workzone_location <|-- user
    class workzone_location{
         +int id
        +string title
        +int primary_location 
        +int secondary_location 
        +int second_secondary_location 
        +int state_id
        +int type_id
        +datetime created_on
        +int created_by_id 
    }

    user_workzone <|-- user
    user_workzone <|-- workzone_location
    class user_workzone{
         +int id
        +string title
        +int workzone_id 
        +int state_id
        +int type_id
        +datetime created_on
        +int created_by_id 
    }

    user_category <|--user
    class user_category{
        +int id
        +string title
        +int category_id
        +int state_id
        +int type_id
        +datetime created_on
        +int created_by_id 
    }

    user_subcategory <|-- user
    user_subcategory <|-- user_category
    class user_subcategory{
        +int id
        +string title
        +int category_id
        +int sub_category_id
        +int state_id
        +int type_id
        +datetime created_on
        +int created_by_id 
    }