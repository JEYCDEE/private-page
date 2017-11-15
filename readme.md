**System requirements:**
-
- PHP >= 7.0
- Laravel >= 5.5
- Redis >= 1.1
- Ubuntu 16.04 LTS

**Ubuntu dependencies:**
-
- ffmpeg
  - For creating thumbnails from video files.
  - Could be installed via command '_sudo apt install ffmpeg_'
  
**Description:**
-
This project is a simple realization of home media and information library.
The main aim is to share information with your family and friends.

While it's in alpha development lot's of features are not currently implemented,
but will be soon. Anyone, who want to participate is welcome.

I see this project as an alternative to google services, such as photos, videos, 
contacts, calendars, etc. while storing all your data inside your own 
private / home web server.

**Manual:**
-
- You can upload photos and videos through ftp / samba client and they are
automatically will be visible on appropriate page.
  - Photos path: storage/app/public/photos/
  - Videos path: storage/app/public/videos/
- You can upload contacts as a simple .csv file through ftp / samba client and
it will be automatically unparsed by a program service.
  - Contacts path: storage/app/storage/public/contacts.csv
- To generate thumbnails for all your existing photos and videos, plese execute
this commands:
  - Photos: '_php artisan thumbnails:photos_'
  - Videos: '_php artisan thumbnails:videos_'
- When clicking on a photo or video thumbnail, we need to have them in our public
folder, that's why we should make aliases for our media files. Let's run this
commands: 
  - Photos: '_php artisan symlinks:photos_'
  - Videos: '_php artisan symlinks:videos_'

**Changes log:**
-
- **Alpha.**
  - Uploaded, released.