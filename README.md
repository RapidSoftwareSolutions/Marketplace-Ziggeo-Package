[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Ziggeo/functions?utm_source=RapidAPIGitHub_ZiggeoFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)
# Ziggeo Package
Capture, curate and manage asynchronous videos/playbacks.
* Domain: [Ziggeo](https://ziggeo.com/)
* Credentials: appToken, appPrivateKey, appEncryptionKey

## How to get credentials: 
1. Get credentials from [Ziggeo  App overview ](https://ziggeo.com) 
 

## Custom datatypes: 
|Datatype|Description|Example
|--------|-----------|----------
|Datepicker|String which includes date and time|```2016-05-28 00:00:00```
|Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
|List|Simple array|```["123", "sample"]``` 
|Select|String with predefined values|```sample```
|Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```
 
## Ziggeo.getVideos
The videos resource allows you to access all single videos. Each video may contain more than one stream.

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| limit        | Number     | Limit the number of returned videos. Can be set up to 100.
| skip         | Number     | Skip the first [n] entries.
| reverse      | Boolean    | Reverse the order in which videos are returned.
| states       | String     | Filter videos by state
| tags         | List       | Filter the search result to certain tags, encoded as a comma-separated string

## Ziggeo.getSingleVideo
Get a single video by token or key.

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video token or key

## Ziggeo.createVideo
Create a new video.

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| file         | File       | Video file to be uploaded
| minDuration  | String     | Minimal duration of video
| maxDuration  | String     | Maximal duration of video
| tags         | List       | Video Tags
| key          | String     | Unique name of video
| volatile     | Boolean    | Automatically removed this video if it remains empty

## Ziggeo.deleteVideo
Delete video by ID or Key

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key

## Ziggeo.updateVideo
Update video by ID or Key

| Field         | Type       | Description
|---------------|------------|----------
| appToken      | credentials| App Token
| appPrivateKey | credentials| App Private Key
| videoId       | String     | Video ID or Key
| minDuration   | String     | Minimal duration of video
| maxDuration   | String     | Maximal duration of video
| tags          | List       | Video Tags
| key           | String     | Unique name of video
| volatile      | Boolean    | Automatically removed this video if it remains empty
| expirationDays| String     | After how many days will this video be deleted

## Ziggeo.getStreams
Get All Streams

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| state        | String     | Filter Streams by state

## Ziggeo.getSingleStream
Get Stream

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| streamId     | String     | Stream ID

## Ziggeo.createStream
Create Stream

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| file         | File       | Video file to be uploaded

## Ziggeo.deleteStream
Delete Stream

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| streamID     | String     | Stream ID

## Ziggeo.createAuthtoken
Create new Authtoken

| Field                | Type       | Description
|----------------------|------------|----------
| appToken             | credentials| App Token
| appPrivateKey        | credentials| App Private Key
| volatile             | Boolean    | Will this object automatically be deleted if it remains empty?
| hidden               | Boolean    | If hidden, the token cannot be used directly.
| expirationDate       | String     | Expiration date for the auth token
| usageExperitationTime| Number     | For how many seconds do you want the session to exist for?
| sessionLimit         | Number     | Maximal number of sessions
| grants               | String     | Permissions this tokens grants

## Ziggeo.getAuthtoken
Get single Authtoken

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| authTokenId  | String     | Authtoken id

## Ziggeo.updateAuthtoken
Update Authtoken

| Field                | Type       | Description
|----------------------|------------|----------
| appToken             | credentials| App Token
| appPrivateKey        | credentials| App Private Key
| authTokenId          | String     | Authtoken id
| volatile             | Boolean    | Will this object automatically be deleted if it remains empty?
| hidden               | Boolean    | If hidden, the token cannot be used directly.
| expirationDate       | String     | Expiration date for the auth token
| usageExperitationTime| Number     | For how many seconds do you want the session to exist for?
| sessionLimit         | Number     | Maximal number of sessions
| grants               | String     | Permissions this tokens grants

## Ziggeo.deleteAuthtoken
Delete Authtoken

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| authTokenId  | String     | Authtoken id

## Ziggeo.pushVideoToPushService
Push video and streams (depends on settings) to service (DropBox, Google drive, ftp etc).

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| serviceToken | String     | Push service token. You can find it in Manage option of you application at Ziggeo.com after creation Integration and Push Service.

## Ziggeo.pushStreamToPushService
Push stream to service (DropBox, Google drive, ftp etc).

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| streamId     | String     | Stream ID
| serviceToken | String     | Push service token. You can find it in Manage option of you application at Ziggeo.com after creation Integration and Push Service.

## Ziggeo.downloadSingleVideo
Get link to download video file

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key

## Ziggeo.downloadVideoThumbnail
Get link to download video thumbnail file

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key

## Ziggeo.downloadStreamVideo
Get link to download stream video file

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| streamId     | String     | Stream ID

## Ziggeo.downloadStreamThumbnail
Get link to download stream thumbnail file

| Field        | Type       | Description
|--------------|------------|----------
| appToken     | credentials| App Token
| appPrivateKey| credentials| App Private Key
| videoId      | String     | Video ID or Key
| streamId     | String     | Stream ID