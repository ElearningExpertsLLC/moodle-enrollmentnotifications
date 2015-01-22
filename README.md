# moodle-enrollmentnotifications

This is a local plugin, which should be installed to local/enrollmentnotifications

Commit messages from the original source repo

commit db1e2ef9ed00c531eb52e4d3fef930b0f4e6b81f
Date:   Wed Sep 17 12:28:06 2014 +0530

    Enrollment Notification - Add Direct Link to Course
    

 local/enrollmentnotifications/lib.php | 1 +
 1 file changed, 1 insertion(+)

commit 07589ab5893ca802a9d762985dcca85db05ddcf9
Date:   Tue Aug 19 14:10:05 2014 -0500

    Implements code review
    

 local/enrollmentnotifications/lib.php | 4 ++--
 1 file changed, 2 insertions(+), 2 deletions(-)

commit b3f8ae574c5113e0d4c82725af40a514b340dd0a
Date:   Mon Jul 14 16:37:47 2014 +0530

    Implemented code review feedback dated July 13 2014

 local/enrollmentnotifications/lib.php | 64 ++++++++++++++++++++++-------------
 1 file changed, 41 insertions(+), 23 deletions(-)

commit bf57a05dbeda816d9b8fcad995834fe81edd88ea
Date:   Fri Jul 11 20:05:06 2014 +0530

    F2F Suppress Notification Checkbox Update - Suppress Enrollment

 local/enrollmentnotifications/lib.php | 4 ++--
 1 file changed, 2 insertions(+), 2 deletions(-)

commit 2e4f8ec7fe59eb12df265351d9cd59d268bbe9e9
Date:   Thu Jul 10 20:27:22 2014 +0530

    Suppress DuplicateEnrollment Notifications customization

 local/enrollmentnotifications/lib.php | 22 ++++++++++++++++++++--
 1 file changed, 20 insertions(+), 2 deletions(-)

commit f41076e3775157969f022fc1dc041d1b055b024c
Date:   Mon Jun 16 10:37:39 2014 -0500

    2.5.10 - smarter email suppress of manual enrol
    
    Never notify on completion import utility
    
    Check for both completion status codes, 75 OR 50

 local/enrollmentnotifications/lib.php | 8 +++++++-
 1 file changed, 7 insertions(+), 1 deletion(-)

commit f758a3c8a04be39a75132568ec8bae2d04a3c087
Date:   Thu May 8 10:11:05 2014 -0500

    Suppress email notification upon RPL upload enrollment
    
    Case: 4382

 local/enrollmentnotifications/lib.php | 17 +++++++++++++++--
 1 file changed, 15 insertions(+), 2 deletions(-)

commit a30fd05e68bcee39f0ab22f6f569893c9f3df8fe
Date:   Mon Jan 20 20:23:40 2014 +0100

    New local plugin: enrollmentnotifications

 local/enrollmentnotifications/db/events.php        | 16 +++++
 .../lang/en/local_enrollmentnotifications.php      | 10 +++
 local/enrollmentnotifications/lib.php              | 75 ++++++++++++++++++++++
 local/enrollmentnotifications/version.php          |  7 ++
 4 files changed, 108 insertions(+)

