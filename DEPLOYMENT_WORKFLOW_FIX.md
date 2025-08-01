# Fix de Flujo de Despliegue - GitHub Actions

## Problema Identificado

El workflow `deploy-to-production.yml` tenía un problema en el despliegue hacia el ambiente de desarrollo (customware.cl). A pesar de que los commits al branch `main` debían desplegarse primero en customware.cl, esto no estaba ocurriendo debido a una condición imposible en el paso FTP.

### Problema Específico

El paso FTP original tenía la condición:
```yaml
if: github.ref == 'refs/heads/main' && github.repository == 'Hatt3rPi/recciuscl'
```

Esta condición era imposible porque:
- El workflow se ejecuta desde el repositorio `Hatt3rPi/reccius`
- Pero la condición verificaba que fuera `Hatt3rPi/recciuscl`
- Resultado: nunca se ejecutaba el FTP a customware.cl desde main

## Solución Implementada

Se agregó un nuevo paso FTP específico para despliegues de main branch a customware.cl, posicionado **antes** del proceso de mirroring:

```yaml
- name: FTP-Deploy-Action para Desarrollo (main)
  if: github.ref == 'refs/heads/main' && github.repository == 'Hatt3rPi/reccius'
  uses: SamKirkland/FTP-Deploy-Action@v4.3.5
  with:
    server: ftp.customware.cl
    username: git_controller@customware.cl
    password: ${{ secrets.FTP_PASSWORD }}
    protocol: ftps
    passive: true
```

### Flujo Corregido

**Antes del fix:**
1. Push a main → Solo mirroring a recciuscl → Solo reccius.cl se actualizaba

**Después del fix:**
1. Push a main → **FTP a customware.cl (PRIMERO)** → Mirroring a recciuscl → Ambos ambientes actualizados

## Ubicación del Cambio

- **Archivo:** `.github/workflows/deploy-to-production.yml`
- **Líneas:** 24-32
- **Posición:** Entre "Configurar SSH para Producción" y "Clonar y preparar el repositorio productivo"

## Verificación

El fix fue probado exitosamente con commit `d8e4d87` y se confirmó que:

✅ customware.cl recibe actualizaciones de main branch  
✅ reccius.cl sigue recibiendo actualizaciones vía mirroring  
✅ Flujo de ambiente_desarrollo permanece intacto  
✅ Prioridad de customware.cl está garantizada  

## Principio de Diseño

La solución mantiene el principio de que **customware.cl (desarrollo) debe recibir cambios ANTES que reccius.cl (producción)**, asegurando que el ambiente de desarrollo tenga prioridad para validar cambios antes de que lleguen a producción.