PackageTracker
==============

There are two main goals of the Package Tracker;

1) To show you where your package is while in transit

2) Using previous deliveries as a guide, try and predict with reasonable accuracy when your package will arrive at it's destination.

Yes, this exists on all decent carrier's websites already.  What this aims to provide is a much better estimate of 
when your package will arrive.  If a package is shipped along a path that is already in the database, we can use the 
past data to predict arrival of the current package.

Whats Done?
=============
Not a whole lot.  As of right now, when you enter a valid tracking number from FedEx (I believe thats the one currently working), 
it will connect with the Carriers API and get the tracking data, parse it, add it to the database, and show you a representation 
on a map of where it is.  The logic of gathering previous trackings is not complete yet.
