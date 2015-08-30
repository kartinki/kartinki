# kartinki change log

## 2.0.0 *(2015-08-30)*
- [BC break] Renamed Result::getId() to Result::getUniqueId() and Result::getExt() to Result::getExtension().
- A lot of code style fixes.

## 1.0.0 *(2015-08-21)*

- Global renamings: "version" to "thumbnail", "config" to "version". 
- createThumbnails now returns Result object, which provides getVersions() method.
- Methods getId() and getExt() added to Result.
