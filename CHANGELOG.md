# kartinki change log

## 2.0.2 *(2015-08-31)*
- [Docs] Updated readme.

## 2.0.1 *(2015-08-31)*
- [Enhancement] Added php7 support.
- A few code style fixes.

## 2.0.0 *(2015-08-30)*
- [BC break] Renamed Result::getId() to Result::getUniqueId().
- [BC break] Renamed Result::getExt() to Result::getExtension().
- A lot of code style fixes.

## 1.0.0 *(2015-08-21)*

- [BC break] Global renamings: "version" to "thumbnail", "config" to "version". 
- [BC break] createThumbnails now returns Result object, which provides getThumbnails() method.
