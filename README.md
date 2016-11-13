# Utils

1. `CurrentTimestampInterface` - Interface specifies a way how to get a current timestamp. Default implementation uses a `time()`, but you can use any implementation, including timestamp locking (great for tests). 