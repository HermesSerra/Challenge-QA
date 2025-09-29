# Testes PHPUnit - Challenge QA

Este diretório contém exemplos de testes unitários e de integração para a API Challenge QA.

## Estrutura dos Testes

```
tests/
├── Unit/                           # Testes unitários
├── Integration/                    # Testes de integração
```

## Como Executar os Testes

```bash
# Todos os testes
vendor/bin/phpunit

# Apenas testes unitários
vendor/bin/phpunit tests/Unit

# Apenas testes de integração
vendor/bin/phpunit tests/Integration

# Com cobertura de código (requer xdebug)
vendor/bin/phpunit --coverage-html coverage/
```
