# Relatório de Testes - Desafio QA

Este documento é um modelo para entrega do resultado:

---

## Dados do Candidato
- **Nome:**  Hermes Renato Serra
- **Email:** hermesrenatoserra@gmail.com 
- **NUmero Contato:** (48)98876-1520

---

## Plano de Testes

### Objetivo
O objetivo principal deste plano de testes foi verificar a funcionalidade, a segurança e a robustez da "Challenge QA API". A avaliação focou em garantir que os endpoints de autenticação e cálculo de juros operam conforme o esperado, validando entradas de dados e identificando possíveis vulnerabilidades e falhas de cálculo.

### Escopo
As seguintes funcionalidades e endpoints foram testados:

Autenticação de Usuário:

POST /api/user/register: Cadastro de novos usuários.

POST /api/user/login: Autenticação de usuários existentes.

Calculadora de Juros:

POST /api/calculator/simple-interest: Cálculo de juros simples.

POST /api/calculator/compound-interest: Cálculo de juros compostos.

POST /api/calculator/installment: Simulação de parcelamento.

### Estratégia de Testes
A abordagem utilizada foi a de testes manuais de API. A ferramenta Postman foi empregada para construir e enviar as requisições HTTP para cada endpoint. Foram aplicados os seguintes tipos de teste:

Testes Funcionais: Verificação dos "caminhos felizes" para garantir que as funcionalidades principais operam corretamente.

Testes de Validação de Entrada (Negativos): Envio de dados inválidos (letras em campos numéricos, valores negativos, formatos incorretos) para observar o comportamento da API.

Testes de Segurança Básicos: Tentativas de injeção de SQL e Script (XSS) para verificar a sanitização das entradas.

### Casos de Teste


---
### Casos de Teste - API de Cadastro de Usuário

| ID | Cenário                   | Entrada                  | Resultado Esperado             | Resultado Obtido                                       | Status     |
|:--:|:-------------------------:|:------------------------:|:------------------------------:|:------------------------------------------------------:|:----------:|
| 01 | Cadastro com dados válidos| `email` e `senha` válidos| Sucesso no cadastro (201)      | `{"success": true}`                                    | **Passou** |
| 02 | E-mail já existente       | `email` repetido         | Erro de e-mail duplicado (409) | `{"success": false, "message": "Email already exists"}`| **Passou** |
| 03 | E-mail em maiúsculas      | `email` em CAIXA ALTA    | Sucesso no cadastro (201)      | `{"success": true}`                                    | **Passou** |
| 04 | **BUG** - E-mail vazio    | `email` como `""`        | Erro de campo obrigatório (400)| Permitiu o cadastro (201)                              | **Falhou** |
| 05 | **BUG** - Senha vazia     | `password` como `""`     | Erro de campo obrigatório (400)| Permitiu o cadastro (201)                              | **Falhou** |
| 06 | **BUG** - E-mail sem "@"  | `email` sem o "@"        | Erro de formato inválido (400) | Permitiu o cadastro (201)                              | **Falhou** |
| 07 | **BUG** - XSS na senha    | `password` com `<script>`| Erro de entrada inválida (400) | Permitiu o cadastro (201)                              | **Falhou** |



---
### Casos de Teste - API de Login

| ID | Cenário                     | Entrada                       |    Resultado Esperado        | Resultado Obtido                                         | Status     |
|:--:|:---------------------------:|:-----------------------------:|:----------------------------:|:--------------------------------------------------------:|:----------:|
| 01 | Login  credenciais válidas  | email e senha corretos        | Sucesso no login (200)       | `{"success": true, "message": "Login successful"}`       | **Passou** |
| 02 | Login  senha incorreta      | email correto, senha errada   | Erro de senha incorreta (401)| `{"success": false, "message": "Password is incorrect"}` | **Passou** |
| 03 | Login  e-mail não cadastrado| `email` inexistente           | usuário não encontrado (404) | `{"success": false, "message": "User not found"}`        | **Passou** |
| 04 | Login  campo de senha vazio | `email` correto, `senha` vazia| Erro de senha incorreta (401)|`{"success": false, "message": "Password is incorrect"}`  | **Passou** |
| 05 | E-mail (Case Insensitive)   | `email` em CAIXA ALTA         | Sucesso no login (200)       | `{"success": true, "message": "Login successful"}`       | **Passou** |
| 06 | Senha (Case Sensitive)      | `senha` em CAIXA ALTA         | Erro de senha incorreta (401)| `{"success": false, "message": "Password is incorrect"}` | **Passou** |
| 07 | Segurança - Tentativa de XSS| `senha` com `<script>`        | Erro de senha incorreta (401)| `{"success": false, "message": "Password is incorrect"}` | **Passou** |
| 08 | Segurança  Force (Melhoria) |Muitas tentativas senha errada | Bloqueio de conta`rate limit`| A API continua respondendo normalmente (401)             | **Passou** |

### Casos de Teste - API de Calculadora de Juros

---
#### Juros Simples

| ID | Cenário                              | Entrada             |   Resultado Esperado              | Resultado Obtido                      | Status     |
|:--:|:------------------------------------:|:-------------------:|:---------------------------------:|:-------------------------------------:|:----------:|
| 01 | Cálculo com taxa inteira             | `rate` como `1`     | Cálculo correto (Juros: 100)      | `{"interest": 100}`                   | **Passou** |
| 02 | Cálculo com taxa decimal (ponto)     | `rate` como `1.5`   | Cálculo correto (Juros: 150)      | `{"interest": 150}`                   | **Passou** |
| 03 | **BUG** - Taxa decimal (vírgula)     | `rate` como `"1,5"` | Calcular juros de 150 ou erro 400 | Calculou juros de 100 (ignorou `,5`)  | **Falhou** |
| 04 | **BUG** - Taxa decimal < 1 (vírgula) | `rate` como `"0,5"` | Calcular juros de 50 ou erro 400  | Calculou juros de 0 (ignorou o valor) | **Falhou** |

---
#### Juros Compostos

| ID | Cenário                                       | Entrada                  | Resultado Esperado             | Resultado Obtido                  | Status     |
|:--:|:--------------------------------------------:|:-------------------------:|:------------------------------:|:---------------------------------:|:----------:|
| 01 | Cálculo com taxa zero                        | `rate` como `0`           | Juros devem ser 0              | `{"interest": 0}`                 | **Passou** |
| 02 | Cálculo com tempo zero                       | `time` como `0`           | Juros devem ser 0              | `{"interest": 0}`                 | **Passou** |
| 03 | **BUG** - Lógica de cálculo (valores padrão) | `P:2000, r:1, t:10, n:10` | Cálculo correto (Juros: ~16.72)| `{"interest": 210.23}` (Incorreto)| **Falhou** |
| 04 | **BUG** - Lógica de cálculo (frequência)     | `P:1000, r:12, t:2, n:12` | Cálculo correto (Juros: 20.10) | `{"interest": 269.73}` (Incorreto)| **Falhou** |
| 05 | **BUG** - Principal negativo                 | `principal` como `-1000`  | Erro de entrada inválida (400) | Calculou com valor negativo (200) | **Falhou** |
| 06 | **BUG** - Frequência zero                    | `frequency` como `0`      | Erro por divisão por zero (400)| `500 Internal Server Error`       | **Falhou** |
| 07 | **BUG** - Tempo > 12 meses                   | `time` como `13`          | Calcular para 13 meses ou erro | Alterou o input `time` para `6.5` | **Falhou** |

---
#### Simulação de Parcelamento

| ID | Cenário                        | Entrada            | Resultado Esperado               | Resultado Obtido                 | Status     |
|:--:|:-------------------------------:|:------------------:|:-------------------------------:|:--------------------------------:|:----------:|
| 01 | Cálculo de parcelamento padrão  | `P:1200, r:1, i:6` | Cálculo correto e saldo final 0 | Sucesso no cálculo (200)         | **Passou** |
| 02 | **BUG** - Cálculo com 1 parcela | `P:2000, r:1, i:1` | Saldo final deve ser 0          | Saldo final negativo (-0.03)     | **Falhou** |
| 03 | **BUG** - Taxa decimal (vírgula)| `rate` como `"1,5"`| Erro de formato inválido (400)  | Erro 400 com mensagem incorreta  | **Falhou** |

Todas as evidências dos testes executados estão armazenadas no diretório anexo, denominado "Evidencias dos testes".

## Falhas Encontradas
API de Cadastro de Usuário
Segurança Crítica: Vulnerabilidade a Injeção de Script (XSS)

![](Evidencias%20dos%20testes/API1/Teste%20de%20segurança%20senha.png)



Descrição: A API permitiu o cadastro de um usuário com um payload de script (<script>alert('XSS Test');</script>) no campo de senha. Embora a senha não seja refletida na tela, armazenar scripts no banco de dados é uma falha de segurança grave que pode levar a ataques complexos.

Impacto: Crítico. Compromete a integridade do banco de dados e abre portas para vulnerabilidades de segurança.

Validação Crítica: Cadastro com Campos Vazios
![Bug Email vazio](Evidencias%20dos%20testes/API1/Bug%20Email%20vazio%20.png)
![Bug senha vazia](Evidencias%20dos%20testes/API1/Bug%20senha%20vazia%20.png)


Descrição: O sistema permitiu o cadastro de usuários com os campos email e/ou password vazios.

Impacto: Crítico. Gera dados inconsistentes e inválidos no sistema, além de potenciais falhas de segurança.

Validação de Dados: Falha na Validação de Formato de E-mail
![Email errado](Evidencias%20dos%20testes/API1/emeil%20errado.png)

Descrição: A API aceitou o cadastro de um e-mail sem o caractere "@" (ex: "hermes_https://www.google.com/search?q=tapahotmail.com"), tratando-o como um e-mail válido.

Impacto: Alto. Permite que dados inválidos sejam inseridos, impossibilitando a comunicação com o usuário.
Liste os bugs encontrados, com descrição clara:  

### API de Calculadora de Juros

Erro Fatal: Divisão por Zero Causa Erro 500
![Divisão por zero causa erro 500](Evidencias%20dos%20testes/API3/Composto/Divisão%20por%20Zero%20Causa%20Erro%20500.png)

Descrição: Ao enviar uma requisição de juros compostos com compounding_frequency igual a 0, a API quebra e retorna um 500 Internal Server Error ao invés de tratar a exceção e retornar um erro 400 (Bad Request).

Impacto: Crítico. Erros não tratados podem causar instabilidade na aplicação.

Validação de Dados: Falha no Tratamento de Decimais com Vírgula
![Bug juros simples 1,5](Evidencias%20dos%20testes/API3/juros%20simples/bug%20juros%20simples%201,5.png)

Descrição: Os endpoints de juros simples e parcelamento não conseguem interpretar números decimais enviados com vírgula (ex: "1,5"). Em vez de converter o valor ou retornar um erro, a API ignora a parte decimal, levando a cálculos incorretos.

Impacto: Alto. Impede o uso correto da API em localidades que usam a vírgula como separador decimal, como o Brasil.

Validação de Dados: Permite Cálculo com Valores Negativos
![Bug cálculo valor negativo](Evidencias%20dos%20testes/API3/Composto/bug%20calculo%20valor%20negativo%20.png)

Descrição: O endpoint de juros compostos aceita e processa um principal com valor negativo, o que não faz sentido em um contexto financeiro. A API deveria validar e rejeitar essa entrada.

Impacto: Médio. A falta de validação de regras de negócio pode gerar resultados inesperados.

Comportamento Inesperado Manipulação de Input do Usuário

![Bug casos extremos](Evidencias%20dos%20testes/API3/Composto/bug%20Verificar%20tratamento%20de%20casos%20extremos%20não%20calcular%20acima%20de%2012%20meses%20.png)

Descrição: No endpoint de juros compostos, ao receber um valor de time maior que 12 (ex: 13), a API alterou o valor internamente para 6.5 sem avisar, em vez de calcular corretamente ou retornar um erro.

Impacto: Médio. A alteração silenciosa dos dados do usuário torna a API pouco confiável.

Precisão/UX: Erro de Arredondamento e Mensagem de Erro Incorreta
![Cálculo 1 mês](Evidencias%20dos%20testes/API3/Parcelado/calculo%201%20mes%20.png)

Descrição: No cálculo de parcelamento para 1 única parcela, o saldo residual ficou negativo (-0.03), indicando um erro de precisão. Adicionalmente, ao receber uma taxa com vírgula, a API retornou uma mensagem de erro genérica ("Principal, rate, and installments are required") em vez de informar sobre o formato inválido do número.

Impacto: Baixo/Médio. Afeta a confiança nos cálculos e a experiência do desenvolvedor ao depurar erros.
---

## Sugestões de Melhoria

[Funcionalidade Essencial] Implementar Fluxo de Recuperação de Senha

A aplicação não possui uma maneira para o usuário recuperar o acesso à conta caso esqueça a senha. Recomenda-se a criação de um endpoint (ex: /api/user/forgot-password) que inicie um fluxo seguro de redefinição de senha, geralmente enviando um link com token de uso único para o e-mail cadastrado.

Segurança: Implementar Bloqueio de Conta por Tentativas de Login (Brute-Force Protection)

A API de Login, embora robusta, não possui proteção contra ataques de força bruta. Recomenda-se implementar uma política de bloqueio temporário de conta (ex: após 5 tentativas de senha incorreta) para aumentar a segurança.

Segurança: Implementar Política de Complexidade de Senha

A API de Cadastro emite um aviso de "senha fraca", mas ainda assim a aceita. Recomenda-se forçar uma política mínima de complexidade (ex: 8 caracteres, letras, números) para garantir que os usuários criem senhas mais seguras.

Robustez: Normalizar E-mails para Minúsculas no Cadastro

Para evitar contas duplicadas (usuario@email.com e USUARIO@email.com), sugere-se converter todos os e-mails para letras minúsculas antes de salvá-los no banco de dados.

UX: Padronizar e Melhorar as Mensagens de Erro

As mensagens de erro da API poderiam ser mais claras e consistentes. Por exemplo, em vez de "campos são requeridos" quando o formato de um número está errado, a mensagem deveria ser específica, como "Formato inválido para o campo 'rate'". Isso melhora a experiência de quem utiliza a API.
## Desenvolvimento / Extras

Se o candidato criou **testes automatizados**, scripts ou qualquer ferramenta extra, pode documentar aqui:  
- Ferramenta utilizada:  
- Repositório ou anexo:  
