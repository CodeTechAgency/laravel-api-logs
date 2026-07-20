---
title: Introduction
weight: 1
group: Getting started
---

Laravel API Logs is a lightweight package that records the requests made to your
Laravel API. Each authenticated request is stored as an `ApiLog` Eloquent model with
the URL, HTTP method, client IP, request data, request headers, response data, and the
request duration — and is linked to the user that made it through a polymorphic
`causer` relation.

- **Log with a single middleware** — append it to your `api` middleware group and
  every authenticated request gets recorded; see [Logging requests](logging-requests.md).
- **Attach logs to any model** — the `HasApiLogs` trait gives your `User` (or any
  other authenticatable model) an `apiLogs` relation to query its history.
- **Redact sensitive values by default** — passwords, tokens, and sensitive headers
  are replaced before a log is stored; see [Redaction](redaction.md).

## How it works

The `LogApiRequest` middleware runs after your application handles the request. When
the request is authenticated, it captures the request and the response, passes both
through the [redactor](redaction.md), and persists an `ApiLog` entry attributed to the
authenticated user. Unauthenticated requests pass through untouched — nothing is
logged for them.
