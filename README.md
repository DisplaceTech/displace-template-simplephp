# Displace Simple PHP Template

A lightweight PHP deployment template for Kubernetes, designed for simple PHP sites without frameworks or databases.

## Overview

This template fills the gap between static sites and full PHP frameworks like Laravel:

- 🐘 **PHP-FPM + Nginx** - Standard PHP production setup
- 📁 **No Database Required** - Perfect for file-based PHP sites
- ☸️ **Kubernetes Native** - Full manifest support with content sync
- 🐳 **Docker Development** - docker-compose for local work
- ⚡ **Content Sync** - Update PHP files without rebuilding
- 🔒 **Automatic HTTPS** - SSL certificates via cert-manager
- 📊 **Built-in Monitoring** - Health checks and resource limits

## Use Cases

This template is ideal for:

- **File repositories** - Directory listing sites like pub.thefragens.com
- **Simple PHP apps** - Contact forms, landing pages, dashboards
- **Legacy PHP sites** - Migrating existing PHP without framework
- **Lightweight APIs** - Simple PHP-based REST endpoints
- **Internal tools** - Quick PHP utilities

## Template Structure

```
templates/
├── .gitignore.tmpl              # Git ignore file
├── .credentials.tmpl            # Project credentials
├── Dockerfile.tmpl              # PHP-FPM + Nginx build
├── Makefile.tmpl                # Cross-platform commands
├── README.md.tmpl               # Generated project docs
├── docker-compose.yaml.tmpl     # Local development
├── nginx.conf.tmpl              # Nginx configuration
├── php.ini.tmpl                 # PHP configuration
├── supervisord.conf.tmpl        # Process manager config
└── manifests/
    ├── 01-namespace.yaml.tmpl
    ├── 02-content-pvc.yaml.tmpl
    ├── 03-deployment.yaml.tmpl
    ├── 04-service.yaml.tmpl
    └── 05-ingress.yaml.tmpl
public/                          # Example PHP content
└── index.php                    # Sample directory listing
```

## Quick Start

### 1. Initialize Your Project

```bash
# Initialize a new simple PHP project
displace project init simplephp

# You'll be prompted for:
# - Project name (e.g., "my-php-site")
# - Kubernetes namespace
# - Domain name (e.g., "files.example.com")
```

### 2. Add Your PHP Files

```bash
cd my-php-site

# Copy your existing PHP files
cp -r /path/to/your/php/files/* public/

# Or start fresh with the included example
```

### 3. Build and Deploy

```bash
# Build Docker image
make build

# Deploy to Kubernetes
make deploy

# Check status
make status
```

### 4. Update Content

```bash
# Edit PHP files locally
vim public/index.php

# Sync to running pods (no rebuild needed)
make sync-content
```

## Template Variables

### Required Variables
| Variable | Description | Example |
|----------|-------------|---------|
| `ProjectName` | Project identifier | `my-php-site` |
| `Namespace` | Kubernetes namespace | `my-php-site` |
| `Domain` | Site domain name | `files.example.com` |

### Optional Variables
| Variable | Default | Description |
|----------|---------|-------------|
| `PHPVersion` | `8.3` | PHP version |
| `Replicas` | `2` | Pod replicas |
| `StorageSize` | `5Gi` | PVC storage size |
| `StorageClass` | `standard` | Kubernetes storage class |
| `IngressClass` | `nginx` | Ingress controller |
| `CertIssuer` | `letsencrypt-prod` | Cert-manager issuer |
| `MemoryLimit` | `256Mi` | Container memory limit |
| `CPULimit` | `200m` | Container CPU limit |
| `UploadMaxSize` | `64M` | Max file upload size |

## Comparison with Other Templates

| Feature | Static | SimplePHP | Laravel |
|---------|--------|-----------|---------|
| PHP Support | ❌ | ✅ | ✅ |
| Database | ❌ | ❌ | ✅ (MySQL) |
| Framework | ❌ | ❌ | ✅ (Laravel) |
| Complexity | Low | Low | High |
| Content Sync | ✅ | ✅ | ❌ |
| Docker Build | ✅ | ✅ | ✅ |
| Use Case | HTML/CSS/JS | Simple PHP | Full apps |

## Available Commands

After project generation:

### Development
- `make build` - Build Docker image
- `make dev` - Start local development
- `make dev-down` - Stop local development
- `make dev-logs` - View local logs

### Deployment
- `make deploy` - Deploy to Kubernetes
- `make destroy` - Remove deployment
- `make status` - Check status
- `make scale REPLICAS=N` - Scale pods

### Content
- `make sync-content` - Sync PHP files to pods
- `make backup-content` - Backup from pods

### Monitoring
- `make logs` - View application logs
- `make events` - View Kubernetes events
- `make shell` - Access pod shell
- `make port-forward` - Local access

## Security Features

- Non-root container execution
- Security headers in Nginx
- OPcache for production
- PHP expose_php disabled
- Hidden file protection
- Automatic HTTPS

## Requirements

- **Kubernetes**: 1.20+
- **Docker**: 20.10+
- **kubectl**: For cluster access

## License

MIT License - See [LICENSE](LICENSE) for details.

---

**Part of the Displace ecosystem** - [displace.tech](https://displace.tech)
