# VeloxRouter RPC

Official JSON-RPC 2.0 microservices communication and dispatching package for the [VeloxRouter](https://github.com/VeloxRouter/router) ecosystem. Built for high performance, zero unnecessary dependencies, and enterprise-grade distributed tracing.

## Installation

Install the package via Composer:

```bash
composer require veloxrouter/rpc

```

## Features

* **JSON-RPC 2.0 Specification**: Full compliance with the JSON-RPC 2.0 protocol for request, response, and error handling.
* **Distributed Tracing**: Automatic generation and propagation of correlation IDs (`X-Correlation-ID`) across inter-service calls.
* **Robust Error Handling**: Typed exceptions and standardized error codes for reliable debugging and fault tolerance.
* **Zero Dependencies**: Lightweight, standalone, and optimized for high-throughput microservices.

## Architecture

This package decouples remote procedure calls into clean abstractions:

* **Client Dispatcher**: Handles HTTP payload marshaling, correlation tracking, and remote method invocation.
* **Error Standards**: Standardized error responses carrying explicit codes, messages, and contextual data.

## License

The VeloxRouter RPC package is open-source software licensed under the [MIT license](https://www.google.com/search?q=LICENSE).
