
# Symfony static serivie

Simple php static serivice to store preview and download files based on AWS S3 and cached with redis

## Usage

#### Upload file

```http
  POST /static/
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `file` | `binary` | File to be uploaded |

#### Preview image

```http
  GET /static/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | File id |

#### Download file
```http
  GET /static/download/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | File id |

