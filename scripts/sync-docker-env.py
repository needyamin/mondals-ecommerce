"""Merge Docker Compose–friendly vars into .env (called from deploy.sh)."""
from __future__ import annotations

import sys
from pathlib import Path

KEYS = [
    "DB_CONNECTION",
    "DB_HOST",
    "DB_PORT",
    "DB_DATABASE",
    "DB_USERNAME",
    "DB_PASSWORD",
    "SESSION_DRIVER",
    "CACHE_STORE",
    "QUEUE_CONNECTION",
]

DEFAULTS = {
    "DB_CONNECTION": "mysql",
    "DB_HOST": "db",
    "DB_PORT": "3306",
    "DB_DATABASE": "mondals_ecommerce",
    "DB_USERNAME": "root",
    "DB_PASSWORD": "root_password",
    "SESSION_DRIVER": "database",
    "CACHE_STORE": "database",
    "QUEUE_CONNECTION": "database",
}

MARKER = "# --- Docker (scripts/deploy.sh) ---"


def main() -> int:
    root = Path(sys.argv[1]).resolve()
    path = root / ".env"
    if not path.is_file():
        return 0

    lines = path.read_text(encoding="utf-8", errors="replace").splitlines()
    vals = {k: DEFAULTS[k] for k in KEYS}
    for ln in lines:
        if not ln.strip() or ln.lstrip().startswith("#") or "=" not in ln:
            continue
        k, _, v = ln.partition("=")
        k = k.strip()
        if k not in vals:
            continue
        v = v.strip().strip('"').strip("'")
        if v:
            vals[k] = v

    rm = set(KEYS)
    out: list[str] = []
    for ln in lines:
        if ln.strip() == MARKER:
            continue
        if not ln.strip() or ln.lstrip().startswith("#"):
            out.append(ln)
            continue
        k = ln.split("=", 1)[0].strip()
        if k in rm:
            continue
        out.append(ln)

    while out and not out[-1].strip():
        out.pop()

    out.append("")
    out.append("# --- Docker (scripts/deploy.sh) ---")
    for k in KEYS:
        v = vals[k]
        if any(c in v for c in ' "\n\\\r\t#'):
            esc = v.replace("\\", "\\\\").replace('"', '\\"')
            out.append(f'{k}="{esc}"')
        else:
            out.append(f"{k}={v}")

    path.write_text("\n".join(out) + "\n", encoding="utf-8")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
