# SimplePHP Template - Analysis Findings

## Target Site Analysis: pub.thefragens.com

### Site Characteristics
- **Type**: File repository / directory listing
- **Technology**: PHP-based directory listing script
- **Database**: None required
- **Framework**: None (vanilla PHP)
- **Content**: Static files served via PHP directory listing

### Key Observations
1. Simple PHP script that lists directory contents
2. Clean, modern UI with file icons, sizes, and dates
3. No authentication or complex backend
4. Lightweight resource requirements
5. Content changes require only file sync, not rebuilds

## Gap Analysis: Existing Templates

| Feature | Static | SimplePHP | Laravel |
|---------|--------|-----------|---------|
| PHP Support | No | Yes | Yes |
| Database | No | No | Yes (MySQL) |
| Framework | No | No | Yes (Laravel) |
| Build Complexity | Low | Low | High |
| Content Sync | Yes | Yes | No |
| Use Case | HTML/JS | Simple PHP | Full apps |

### Why SimplePHP Template is Needed
The static template cannot run PHP. The Laravel template is overkill for:
- Directory listing sites
- Contact forms
- Simple dashboards
- Legacy PHP migrations
- File-based PHP apps

SimplePHP fills this gap with minimal overhead.

## Template Design Decisions

### 1. PHP-FPM + Nginx (Not Apache)
**Rationale**:
- Better performance than mod_php
- Industry standard for production PHP
- Consistent with Laravel template
- Easier resource management

### 2. Supervisord Process Manager
**Rationale**:
- Single container runs both PHP-FPM and Nginx
- Simplified deployment (1 pod type)
- Proper process lifecycle management
- Clean health check integration

### 3. PVC-Based Content Sync
**Rationale**:
- Update PHP files without rebuilding containers
- `make sync-content` for rapid iteration
- Consistent with static template approach
- Enables backup/restore workflows

### 4. No Init Container
**Rationale**:
- Unlike static template, PHP files are baked into image
- PVC provides runtime overlay capability
- Simpler deployment sequence
- Content sync is optional enhancement

## Template Structure

```
displace-template-simplephp/
├── template.yaml              # Displace configuration
├── README.md                  # Template documentation
├── LICENSE                    # MIT License
├── public/                    # Example PHP content
│   └── index.php              # Sample directory listing
└── templates/
    ├── .credentials.tmpl
    ├── .gitignore.tmpl
    ├── Dockerfile.tmpl        # PHP-FPM + Nginx build
    ├── Makefile.tmpl          # Project commands
    ├── README.md.tmpl         # Generated project docs
    ├── docker-compose.yaml.tmpl
    ├── nginx.conf.tmpl
    ├── php.ini.tmpl
    ├── supervisord.conf.tmpl
    └── manifests/
        ├── 01-namespace.yaml.tmpl
        ├── 02-content-pvc.yaml.tmpl
        ├── 03-deployment.yaml.tmpl
        ├── 04-service.yaml.tmpl
        └── 05-ingress.yaml.tmpl
```

## Template Variables

### Required
| Variable | Description | Example |
|----------|-------------|---------|
| `ProjectName` | Project identifier | `file-repo` |
| `Namespace` | K8s namespace | `file-repo` |
| `Domain` | Site domain | `pub.example.com` |

### Optional (with defaults)
| Variable | Default | Description |
|----------|---------|-------------|
| `PHPVersion` | `8.3` | PHP version |
| `Replicas` | `2` | Pod replicas |
| `StorageSize` | `5Gi` | PVC size |
| `StorageClass` | `standard` | K8s storage class |
| `IngressClass` | `nginx` | Ingress controller |
| `CertIssuer` | `letsencrypt-prod` | Cert-manager issuer |
| `MemoryLimit` | `256Mi` | Container memory |
| `CPULimit` | `200m` | Container CPU |
| `UploadMaxSize` | `64M` | Max file upload |

## Security Features

1. **Non-root execution**: Container runs as www-data (UID 82)
2. **Security headers**: X-Frame-Options, X-Content-Type-Options, etc.
3. **Hidden file protection**: Nginx blocks access to dotfiles
4. **OPcache**: Enabled for production performance
5. **PHP hardening**: expose_php=Off, limited functions
6. **Automatic HTTPS**: Via cert-manager integration

## Consistency with Other Templates

### Standardized Elements
- Same Makefile target names (deploy, destroy, status, logs, etc.)
- Same Kubernetes label scheme (`app.kubernetes.io/*`)
- Same template.yaml structure
- Same manifest file naming (01-05 numbered)
- Same .credentials pattern

### Template-Specific Elements
- PHP-FPM + Nginx configuration
- sync-content/backup-content targets
- OPcache configuration
- PHP-specific health checks

## Recommended Use Cases

1. **File repositories** - Directory listings like pub.thefragens.com
2. **Simple PHP apps** - Contact forms, landing pages
3. **Legacy PHP sites** - Migrate existing vanilla PHP
4. **Lightweight APIs** - Simple REST endpoints
5. **Internal tools** - Quick PHP utilities

## Migration Path

For customers outgrowing SimplePHP:
- **Need database**: Migrate to WordPress or Laravel template
- **Need framework**: Migrate to Laravel template
- **Need static only**: Downgrade to Static template

---

**Analysis completed**: 2024-12-13
**Template version**: 1.0.0
