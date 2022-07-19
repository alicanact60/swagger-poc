<?php
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: POST,GET,OPTIONS,PUT,DELETE,PATCH");
 header("Content-Type: text/xml");
?>openapi: 3.0.0
info:
  title: Alias Directory API Specifications
  description: Alias Directory provides services to map and resolve an identifier; referred to as an “Alias” such as mobile phone number or email address to a payment credential such as a bank account or card number. The purpose of using an Alias for payment is that it is intuitive, easy to share, and avoids exposing sensitive information when initiating a payment. <br /> <br /> These are the API specifications for Alias Directory for currently available API endpoints. This list will be continually updated with new APIs.

  version: 2.0.0
  license:
    name: "YellowPepper"
    url: "https://www.haker101.com"

tags:
  - name: Alias Lifecycle Management
    description: These APIs are used to manage the lifecycle of an Alias (e.g., Create, Update, Delete Alias)
  - name: Alias Resolution
    description: Resolve an Alias to retrieve the payment credential associated with the Alias
  - name: Payment Credentials Lyfecycle Management
    description: APIs to manage the lifecyle of Payment Credentials individually

servers:
  - url: https://{environment}.haker101.com/gateway
    description: Alias Directory API Specifications
    variables:
      environment:
        enum:
          - 'directory-cde-stg'
          - 'directory-stg'
        default: 'directory-stg'

paths:
  /aliases:
    post:
      operationId: createAlias
      description: This API is used to create an Alias in the Alias Directory and associate one or more payment credentials with the Alias.
      summary: Create Alias
      security:
        - oauth2-client-credentials: []
      tags:
        - Alias Lifecycle Management
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/CreateAliasRequest'
      responses:
        200:
          $ref: '#/components/responses/CreateAliasResponse'
        400:
          $ref: "#/components/responses/400BadInput"
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'

  /aliases/inquiry:
    post:
      operationId: aliasInquiry
      description: Looks for a group of aliases to check if present on the directory, it returns a list of aliases found and a summary(total, repeated, found, no found) of the inquiry.
      summary: Inquiry Alias
      security:
        - oauth2-client-credentials: []
      tags:
        - Alias Lifecycle Management
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/AliasInquiryRequest'
      responses:
        200:
          $ref: '#/components/responses/AliasInquiryResponse'
        400:
          $ref: "#/components/responses/400BadInput"
        404:
          $ref: '#/components/responses/404NotFound'
        500:
          $ref: '#/components/responses/500InternalError'

  /aliases/{aliasId}:
    put:
      operationId: updateAlias
      description: Update the information associated with the Alias such as the identification, profile, or consent.
      summary: Update Alias
      parameters:
        - $ref: '#/components/parameters/aliasId'
      security:
        - oauth2-client-credentials: [ ]
      tags:
        - Alias Lifecycle Management
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/UpdateAliasRequest'
      responses:
        200:
          $ref: '#/components/responses/UpdateAliasResponse'
        400:
          $ref: "#/components/responses/400BadInput"
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'
    get:
      operationId: getAlias
      description: Retrieves the information about a specific Alias
      summary: Get Alias
      parameters:
        - $ref: '#/components/parameters/aliasId'
      security:
        - oauth2-client-credentials: [ ]
      tags:
        - Alias Lifecycle Management
      responses:
        200:
          $ref: '#/components/responses/GetAliasResponse'
        400:
          $ref: "#/components/responses/400BadInput"
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'
    delete:
      tags:
        - Alias Lifecycle Management
      summary: Delete Alias
      description: Deletes a specific Alias and the associated payment credentials. If the Alias is shared by multiple clients,
      operationId: deleteAlias
      security:
        - oauth2-client-credentials: [ ]
      parameters:
        - $ref: '#/components/parameters/aliasId'
      responses:
        204:
          $ref: '#/components/responses/204NoContent'
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'

  /aliases/resolve:
    post:
      tags:
        - Alias Resolution
      summary: Alias Resolution
      description: Retrieve information about an Alias and the payment credential(s) associated with the Alias. This information can then be used to initiate a funds movement transaction. The parameters in the response may vary depending on the program configuration.
      operationId: aliasResolve
      security:
        - oauth2-client-credentials: [ ]
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/AliasResolveRequest'
      responses:
        200:
          $ref: '#/components/responses/AliasResolveResponse'
        400:
          $ref: '#/components/responses/400BadInput'
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'

  /aliases/{aliasId}/paymentCredentials:
    post:
      tags:
        - Payment Credentials Lyfecycle Management
      summary: Add Payment Credentials
      description: Add a new *Payment Credential* to an existing **Alias**.
      operationId: createPaymentCredential
      security:
        - oauth2-client-credentials: []
      parameters:
        - $ref: '#/components/parameters/aliasId'
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/CreatePaymentCredentialRequest'
      responses:
        200:
          $ref: '#/components/responses/CreatePaymentCredentialResponse'
        400:
          $ref: '#/components/responses/400BadInput'
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'
    get:
      tags:
        - Payment Credentials Lyfecycle Management
      summary: Get Payment Credentials
      description: Get *Payment Credentials* of existing **Alias**.
      operationId: getPaymentCredentials
      security:
        - oauth2-client-credentials: []
      parameters:
        - $ref: '#/components/parameters/aliasId'
      responses:
        200:
          $ref: "#/components/responses/GetPaymentCredentialsResponse"
        400:
          $ref: '#/components/responses/400BadInput'
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'

  /paymentCredentials/{paymentCredentialId}:
    get:
      tags:
        - Payment Credentials Lyfecycle Management
      summary: Get Payment Credential
      description: Gets details for the requested Payment Credential
      operationId: getPaymentCredential
      security:
        - oauth2-client-credentials: []
      parameters:
        - $ref: '#/components/parameters/paymentCredentialId'
      responses:
        200:
          $ref: '#/components/responses/PaymentCredentialGetResponse'
        400:
          $ref: '#/components/responses/400BadInput'
        404:
          $ref: '#/components/responses/404NotFound'
        500:
          $ref: '#/components/responses/500InternalError'
    delete:
      tags:
        - Payment Credentials Lyfecycle Management
      summary: Delete Payment credential
      description: Delete a specific Payment Credential.
      operationId: deletePaymentCredential
      security:
        - oauth2-client-credentials: [ ]
      parameters:
        - $ref: '#/components/parameters/paymentCredentialId'
      responses:
        204:
          $ref: '#/components/responses/204NoContent'
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'
    put:
      tags:
        - Payment Credentials Lyfecycle Management
      summary: Update Payment Credential
      description: Updades and existing *Payment Credential* to an existing **Alias**.
      operationId: updatePaymentCredential
      security:
        - oauth2-client-credentials: []
      parameters:
        - $ref: '#/components/parameters/paymentCredentialId'
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/UpdatePaymentCredentialRequest'
      responses:
        204:
          $ref: '#/components/responses/204NoContent'
        400:
          $ref: '#/components/responses/400BadInput'
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'

  /aliases/getByExternalId:
    post:
      operationId: getByExternalId
      description: Find internal **id** (for the specified entity **type**) associated to the **externalId** initially passed in during the **create** operation
      summary: Get By External ID
      security:
        - oauth2-client-credentials: []
      tags:
        - External IDs
      requestBody:
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/GetByExternalIdRequest'
      responses:
        200:
          $ref: '#/components/responses/GetByExternalIdResponse'
        400:
          $ref: "#/components/responses/400BadInput"
        404:
          $ref: '#/components/responses/404NotFound'
        422:
          $ref: '#/components/responses/422UnprocessableEntity'
        500:
          $ref: '#/components/responses/500InternalError'

components:
  responses:
    PaymentCredentialGetResponse:
      description: OK - Get a Payment Credential Response
      content:
        application/json:
          schema:
            $ref: "#/components/schemas/PaymentCredentialItemGetResponse"
    GetPaymentCredentialsResponse:
      description: OK - Get Payment Credentials Response
      content:
        application/json:
          schema:
            $ref: '#/components/schemas/PaymentCredentialsList'
    AliasResolveResponse:
      description: OK - Alias Resolution response
      content:
        application/json:
          schema:
            $ref: '#/components/schemas/AliasResolveBasicResponse'
    CreateAliasResponse:
      description: OK - Create Alias Response
      content:
        application/json:
          schema:
            type: object
            properties:
              id:
                $ref: "#/components/schemas/AliasId"
              externalId:
                $ref: "#/components/schemas/AliasExternalId"
              paymentCredentials:
                type: array
                items:
                  $ref: "#/components/schemas/PaymentCredentialCreateUpdateResponse"
            required:
              - id
              - paymentCredential
    CreatePaymentCredentialResponse:
      description: OK - Add Payment Credentials Response
      content:
        application/json:
          schema:
            type: object
            properties:
              id:
                $ref: "#/components/schemas/PaymentCredentialId"
              externalId:
                $ref: "#/components/schemas/PaymentCredentialExternalId"
              type:
                $ref: "#/components/schemas/PaymentCredentialType"
            required:
              - id
              - type
    UpdateAliasResponse:
      description: OK - Update Alias Response
      content:
        application/json:
          schema:
            type: object
            properties:
              id:
                $ref: "#/components/schemas/Id"
              externalId:
                $ref: "#/components/schemas/AliasExternalId"
    GetAliasResponse:
      description: OK - Get Alias Response
      content:
        application/json:
          schema:
            type: object
            allOf:
              - $ref: '#/components/schemas/Consent'
              - $ref: '#/components/schemas/BaseAliasRequest'
            properties:
              aliasValue:
                type: string
                minLength: 1
                maxLength: 128
                example: "1231231234"
                description: The value of the Alias, e.g., phone number or email address
              aliasType:
                type: string
                example: PHONE
                description: The type of Alias in aliasValue
                enum:
                  - PHONE
                  - EMAIL
              status:
                type: string
                example: 'Active'
                description: The status of the Alias, e.g., Active
              createdOn:
                allOf:
                  - $ref: "#/components/schemas/DateTime"
                example: '2021-01-01T22:52:46.000Z'
                description: Date when the record was created, in ISO UTC format YYYY-MM-DDThh:mm:ss.000Z
              lastModifiedOn:
                allOf:
                  - $ref: "#/components/schemas/DateTime"
                example: '2021-01-01T22:52:46.000Z'
                description: Date when the record was last modified, in ISO UTC format YYYY-MM-DDThh:mm:ss.000Z
    GetByExternalIdResponse:
      description: OK - Get External ID Response
      content:
        application/json:
          schema:
            type: object
            properties:
              id:
                allOf:
                  - $ref: "#/components/schemas/AliasId"
                description: Requested internal `id`
            required:
              - id
    200_OK:
      description: The request succeeded
    204NoContent:
      description: Success - No content
    400BadInput:
      description: |
        Bad Request. Returned when any field validations fails or when any required fields is missing or when request is inconsistent with the Request definitions.
      content:
        application/problem+json:
          schema:
            type: object
            allOf:
              - $ref: "#/components/schemas/ErrorResponse"
            properties:
              errorCode:
                type: string
                example: 400
                description: Code to identify the type of error. The value will be `400` in this case.
    404NotFound:
      description: |
        Not Found. The server can't find the requested resource.
      content:
        application/problem+json:
          schema:
            type: object
            allOf:
              - $ref: "#/components/schemas/ErrorResponse"
            properties:
              errorCode:
                type: string
                example: 404
                description: Code to identify the type of error. The value will be `404` in this case.
    422UnprocessableEntity:
      description: |
        Unprocessable Entity
        The server understands the content type of the request entity, and the syntax of the request entity is correct, but it was unable to process the contained instructions.
      content:
        application/problem+json:
          schema:
            allOf:
              - $ref: "#/components/schemas/ErrorResponse"
            properties:
              errorCode:
                type: string
                example: 422
                description: Code to identify the type of error. The value will be `422` in this case.
              externalErrorCode:
                type: string
                example: 1023
                description: Error code returned by third party service in case there was an interaction
    500InternalError:
      description: |
        Internal Server Error
      content:
        application/problem+json:
          schema:
            type: object
            allOf:
              - $ref: "#/components/schemas/ErrorResponse"
            properties:
              errorCode:
                type: string
                example: 500
                description: Code to identify the type of error. The value will be `500` in this case.
    AliasInquiryResponse:
      description: Inquiry alias response
      content:
        application/json:
          schema:
            $ref: '#/components/schemas/AliasInquiryResponseInternal'
  securitySchemes:
    oauth2-client-credentials:
      type: oauth2
      description: OAuth 2.0 Using Client Credentials flow
      flows:
        clientCredentials:
          tokenUrl: http://directory-dev.haker101.com/token
          scopes: {} # does not use scopes
  schemas:
    Address:
      type: object
      properties:
        addressLine1:
          type: string
          maxLength: 60
          example: 1000 Market Street
          description: Address line 1
        addressLine2:
          type: string
          maxLength: 60
          example: Suite 101
          description: Address line 2
        streetName:
          type: string
          description: Street name
          example: 12
        buildingNumber:
          type: string
          description: Building number
          example: 56
        city:
          type: string
          maxLength: 35
          example: San Francisco
          description: City
        postalCode:
          type: string
          minLength: 1
          maxLength: 16
          example: 94105
        state:
          type: string
          example: CA
          description: State
        minorSubdivisionCode:
          type: string
          minLength: 1
          maxLength: 50
          description: Minor Subdivision Code
          example: CA
        country:
          type: string
          minLength: 3
          maxLength: 3
          example: USA
          description: Country code using 3-character ISO 3166 (Alpha 3) format
      required:
        - country
    AliasResolveBasicResponse:
      type: object
      allOf:
        - $ref: '#/components/schemas/BaseAliasRequest'
      properties:
        paymentCredentials:
          type: array
          description: List of payment credentials linked to an Alias
          items:
            $ref: "#/components/schemas/PaymentCredentialItemAliasResponse"
        directoryName:
          type: string
          description: Name of the external directory where was found the payment
          example: YellowPepper
    AliasResolveRequest:
      required:
        - aliasValue
        - aliasType
      type: object
      description: Alias information to resolve and return payment credentials
      properties:
        aliasValue:
          type: string
          minLength: 1
          maxLength: 128
          example: "1231231234"
          description: The value of the Alias, e.g., phone number or email address.
        aliasType:
          type: string
          example: PHONE
          description: The type of Alias provided in aliasValue
          enum:
            - PHONE
            - EMAIL
        filters:
          type: array
          items:
            type: object
            properties:
              field:
                type: string
                example: DIRECTORY_NAME
                description: Name of the filter to apply
                enum:
                  - DIRECTORY_NAME
              value:
                type: array
                items:
                  type: string
                  maxLength: 36
                  example: STANDARD_YELLOWPEPPER
                  description: Values for the filter
    AlphaCountryCode:
      type: string
      description: this field contains ISO3166 country code (Alpha-3).
      minLength: 3
      maxLength: 3
      example: "USA"
    AlphaCurrencyCode:
      type: string
      minLength: 3
      maxLength: 3
      example: USD
    AccountName:
      type: string
      description: When present, this field contains recipient’s account name at the bank.
      minLength: 1
      maxLength: 50
      example: John Doe
    AccountNumber:
      type: string
      description: When present, this field contains recipient’s account number at the bank.
      minLength: 1
      maxLength: 64
      example: "3012345678901432"
    Bank:
      type: object
      required:
        - bankName
        - accountName
        - accountNumber
        - accountNumberType
        - countryCode
        - currencyCode
      properties:
        type:
          description: |
            In this case, value must be: `BANK`
          example: BANK
        bankName:
          allOf:
            - $ref: "#/components/schemas/BankName"
          description: |
            Bank name of the payment credential

            ISO20022 field name: Creditor Agent Financial Institution Name
          example: Bank of America
        accountName:
          allOf:
            - $ref: "#/components/schemas/AccountName"
          maxLength: 70
          description: |
            Bank account name, i.e., accountholder name as recorded on the bank account

            ISO20022 field name: Creditor Account Name
          example: Alex Miller
        accountNumber:
          allOf:
            - $ref: "#/components/schemas/AccountNumber"
          maxLength: 34
          description: |
            Bank account number

            ISO20022 field name: Creditor Account Number or IBAN
          example: "4111111145551140"
          type: string
        accountNumberType:
          type: string
          enum:
            - IBAN
            - DEFAULT
          description: |
            Bank account number type. Valid values: "IBAN" and "DEFAULT". If the IBAN format is not supported in a country, use DEFAULT.
          example: DEFAULT
        accountType:
          type: string
          enum:
            - CHECKING
            - SAVING
            - MAESTRA
            - VISTA
            - LOAN
          description: Bank account type
        countryCode:
          allOf:
            - $ref: "#/components/schemas/AlphaCountryCode"
          description: |
            Bank account country code. <br> </br>
            Format: 3-character ISO-3166 (Alpha-3) country code

            ISO20022 field name: Creditor Agent Financial Institution Country Code
          example: "USA"
        BIC:
          allOf:
            - $ref: "#/components/schemas/BIC"
          description: |
            Bank account Business Identifier Code (BIC)

            Conditional for select markets. 

            ISO20022 field name: Creditor Agent Financial Institution BICFI
        bankCode:
          type: string
          minLength: 1
          maxLength: 12
          example: 173
          description: |
            Bank account bank code

            Conditional for select markets. 

            ISO20022 field name: Creditor Agent Financial Institution Clearing System Member Identification
        bankCodeType:
          type: string
          enum:
            - ABA
            - SORT_CODE
            - DEFAULT
          description: |
            Bank account bank code type

            Conditional for select markets. 

            ISO20022 field name: Creditor Agent Financial Institution Clearing System Member Identification Type
        branchCode:
          type: string
          minLength: 1
          maxLength: 12
          example: "123456"
          description: |
            Bank account branch code

            Conditional for select markets. 

            ISO20022 field name: Creditor Agent Branch Identification
        currencyCode:
          allOf:
            - $ref: "#/components/schemas/AlphaCurrencyCode"
          description: |
            Bank account currency code

            Format: 3-character ISO-4217 (Alpha-3) currency code

            ISO20022 field name: Creditor Account Currency
          example: "USD"
    BankAliasResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/Bank"
        - $ref: '#/components/schemas/PaymentCredentialOnePreferredOn'
    BankGetResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/BankAccountDetails"
        - $ref: "#/components/schemas/PaymentCredentialAuditTrail"
      properties:
        id:
          $ref: '#/components/schemas/PaymentCredentialId'
    BankAccountDetails:
      type: object
      allOf:
        - $ref: '#/components/schemas/PaymentCredentialCommon'
        - $ref: "#/components/schemas/Bank"
      properties:
        type:
          description: In this case, value must be `BANK`
    BankName:
      type: string
      minLength: 1
      maxLength: 50
      example: Bank of America
    BIC:
      type: string
      minLength: 8
      maxLength: 11
      description: |
        Business Identifier Code (BIC) of the Sponsor FI (Acquirer)
        ISO20022 field name: Debtor Agent FI BIC
      example: "12345678"
    Card:
      type: object
      required:
        - accountNumber
      properties:
        type:
          description: |
            In this case, value must be: `CARD`
          example: CARD
        nameOnCard:
          type: string
          example: John Doe
          description: Name that appears on the Card
        accountNumber:
          type: string
          pattern : ^\d*$
          minLength: 6
          maxLength: 19
          description: |
            Primary account number of the card
          example: "4111111145551142"
        expirationDate:
          type: string
          description: |
            **Conditional**. Expiration date for the card. This is required for certain markets.
          format: YYYY-MM
        billingAddress:
          $ref: "#/components/schemas/Address"
    UpdateCard:
      type: object
      required:
        - accountNumber
      properties:
        type:
          description: |
            In this case, value must be: `CARD`
          example: CARD
        nameOnCard:
          type: string
          example: John Doe
          description: Name that appears on the Card
        accountNumber:
          type: string
          description: |
            Primary account number of the card
          example: "4111111145551142"
        billingAddress:
          $ref: "#/components/schemas/Address"
    CardAliasResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/Card"
        - $ref: '#/components/schemas/PaymentCredentialOnePreferredOn'
    CardGetResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/CardDetails"
        - $ref: "#/components/schemas/PaymentCredentialAuditTrail"
      properties:
        id:
          $ref: '#/components/schemas/PaymentCredentialId'
    CardDetails:
      type: object
      allOf:
        - $ref: '#/components/schemas/PaymentCredentialCommon'
        - $ref: "#/components/schemas/Card"
      properties:
        type:
          description: In this case, the value must be `CARD`
          example: "CARD"
    CardUpdateDetails:
      type: object
      allOf:
        - $ref: "#/components/schemas/UpdateCard"
        - $ref: '#/components/schemas/PaymentCredentialCommon'
      properties:
        type:
          description: In this case, the value must be `CARD`
          example: "CARD"
    CCIN:
      type: object
      description: |
        See more details at: https://developer.payments.ca/ccin-data-model
      required:
        - cycleNumber
        - cycleDate
        - ccin
        - dateCcinIssued
      properties:
        type:
          description: |
            In this case, value must be: `CCIN`
          example: CCIN
        cycleNumber:
          type: integer
          example: 1
          description: The cycle number that this data belongs to
        cycleDate:
          type: string
          format: date
          example: "2022-01-13"
          description: The cycle date that this data belongs to
        ccin:
          type: string
          example: "90000002"
          description: |
            The CCIN for this record. 
            Corporate Creditor Identification Number or 'CCIN' means the identification number issued 
            by the CPA to the Corporate Creditor for the purpose of processing payments.
        dateCcinIssued:
          type: string
          format: date
          example: "1996-01-01"
          description: Date on which the Corporate Creditor Identification Number was issued
        effectiveFrom:
          type: string
          format: date
          example: "1996-01-01"
          description: The effective from date
        shortname:
          type: string
          maximum: 40
          example: "TEST NAME"
          description: The short name for the given record, maximum 40 characters
        address1:
          type: string
          maximum: 43
          example: 9999 Test Address
          description: |
            The first address line for the given record, 
            derived from 2 line address and city:
            Maximum 43 characters.
        address2:
          type: string
          maximum: 43
          example: Address line 2
          description: |
            The second address line for the given record, 
            derived from 2 line address and city:
            Maximum 43 characters.
        address3:
          type: string
          maximum: 43
          example: Address line 3
          description: |
            The third address line for the given record, 
            derived from 2 line address and city:
            Maximum 43 characters.
        address4:
          type: string
          maximum: 43
          example: Address line 4
          description: |
            The fourth address line for the given record, 
            derived from 2 line address and city:
            Maximum 43 characters.
        provCode:
          type: string
          maximum: 2
          example: "ON"
          description: The province for the given record, maximum of 2 characters
        countryCode:
          type: string
          maximum: 20
          enum:
            - CANADA
            - UNITED STATES
          example: "CANADA"
          description: |
            The country code for the given record, maximum of 20 characters
        postal:
          type: string
          maximum: 9
          example: "H0H 0H0"
          description: The postal/zip code the given record, maximum of 9 characters
        acceptableMediaType:
          type: string
          minimum: 1
          maximum: 9
          enum:
            - "1"
            - "2"
          example: "1"
          description: |
            The acceptable media type for the given ccin:
            * 1: is PAPER
            * 2: is PAPER & ELECTRONIC
            Maximum of 9 characters.
        returnCheckDprn:
          type: string
          maximum: 9
          example: "089999999"
          description: The return DPRN for a check, maximum of 9 characters
        ccinStatus:
          type: string
          enum:
            - I
            - A
            - D
            - P
          example: "A"
          description: |
            The status for a given record. Original Name: `status`. The values are: 
            * A is ACTIVE 
            * I is INACTIVE 
            * D is DELETED 
            * P is PENDING
        statusDate:
          type: string
          format: date
          example: "1996-01-01"
          description: The date the current status was set for the given record
        sicCode:
          type: string
          example: "9999"
          description: The SIC code for the given
        action:
          type: string
          enum:
            - A
            - C
            - D
          example: "A"
          description: |
            The action performed on the CCIN. The values are:
            * A: Added
            * C: Changed
            * D: Deleted
        leadFi:
          type: object
          properties:
            leadFinNum:
              type: integer
              maximum: 4
              example: 1
              description: The Lead financial institution number for the given record, maximum of 4 characters
            leadFinName:
              type: string
              example: "FI Name"
              description: The Lead financial institution name for the given record, maximum of 40 characters
    CCINAliasResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/CCIN"
        - $ref: '#/components/schemas/PaymentCredentialOnePreferredOn'
    CCINGetResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/CCINDetails"
        - $ref: "#/components/schemas/PaymentCredentialAuditTrail"
      properties:
        id:
          $ref: '#/components/schemas/PaymentCredentialId'
    CCINDetails:
      type: object
      allOf:
        - $ref: '#/components/schemas/PaymentCredentialCommon'
        - $ref: "#/components/schemas/CCIN"
      properties:
        type:
          description: In this case, value must be `CCIN`
          example: "CCIN"
    Consent:
      type: object
      properties:
        consent:
          type: object
          description: Consent details
          properties:
            presenter:
              type: string
              description: Identifies the presenter of consent to the consumer
              example: "Wells"
            intermediaries:
              type: array
              description: Intermediary parties involved in the consent grant
              minItems: 0
              maxItems: 5
              items:
                type: string
                example: "Google"
            validFromDate:
              type: string
              format: date-time
              example: "2021-12-01T10:00:00.000Z"
              description: Date/Time from which the consent is valid, in ISO UTC format YYYY-MM-DDThh:mm:ss.000Z
            expiryDateTime:
              type: string
              format: date-time
              example: '2022-06-20T10:00:00.000Z'
              description: Date/Time when the consent expires, in ISO UTC format YYYY-MM-DDThh:mm:ss.000Z
            version:
              type: string
              minLength: 1
              maxLength: 9
              example: "1.0"
              description: Specific version of the terms and conditions that the customer accept for the user solution.
          required:
            - presenter
            - validFromDate
            - version
    CountryCode:
      type: string
      description: this field contains ISO3166 country code of recipient’s bank.
      minLength: 3
      maxLength: 3
      pattern: ^\d\d\d$
      example: "840"
    CreateAliasRequest:
      type: object
      allOf:
        - $ref: '#/components/schemas/Consent'
        - $ref: '#/components/schemas/BaseAliasRequest'
      properties:
        aliasValue:
          type: string
          minLength: 1
          maxLength: 128
          example: "1231231234"
          description: The value of the Alias, e.g., phone number or email address.
        aliasType:
          type: string
          example: PHONE
          description: The type of Alias provided in aliasValue
          enum:
            - PHONE
            - EMAIL
        externalId:
          $ref: "#/components/schemas/AliasExternalId"
        paymentCredentials:
          type: array
          minItems: 1
          maxItems: 10
          description: |
            List of payment credentials to be linked to the Alias. Up to 10 different payment credentials of any type can be added in a single Create Alias API call.
          items:
            $ref: "#/components/schemas/PaymentCredentialItem"
      required:
        - aliasValue
        - aliasType
        - paymentCredential
        - consent
    UpdatePaymentCredentialRequest:
      type: object
      anyOf:
        - $ref: "#/components/schemas/CardUpdateDetails"
        - $ref: "#/components/schemas/BankAccountDetails"
    CreatePaymentCredentialRequest:
      type: object
      anyOf:
        - $ref: "#/components/schemas/CardDetails"
        - $ref: "#/components/schemas/BankAccountDetails"
    UpdateAliasRequest:
      type: object
      allOf:
        - $ref: '#/components/schemas/Consent'
        - $ref: '#/components/schemas/BaseAliasRequest'
    CurrencyCode:
      type: string
      minLength: 3
      maxLength: 3
      pattern: ^\d\d\d$
      example: USD
    Date:
      type: string
      description: Date ISO-8601 Format https://www.w3.org/TR/NOTE-datetime
      pattern: ^\d\d\d\d\-\d\d\-\d\d$
      example: "1980-02-01"
      minLength: 10
      maxLength: 10
    ErrorResponse:
      type: object
      properties:
        errorCode:
          type: string
          example: 404
          description: Code to identify the type of error. Fixed 400 in this case
        errorMessages:
          type: array
          items:
            type: string
            example: alias does not exist
          description: Description of the error reason
      required:
        - errorCode
        - errorMessages
    GetByExternalIdRequest:
      type: object
      properties:
        externalId:
          allOf:
            - $ref: "#/components/schemas/AliasExternalId"
          description: External Id (maintained by client) associated to the entity `type`
        type:
          type: string
          enum:
            - ALIAS
            - PAYMENT_CREDENTIAL
          description: The entity type associated to the external Id
      required:
        - externalId
        - type
    Id:
      type: string
      minLength: 36
      maxLength: 36
      description: UUID to uniquely identify an object.
      example:  d1162433-d7f9-443a-9a91-0aaa1eb29a7c
    AliasId:
      allOf:
        - $ref: "#/components/schemas/Id"
      description: This is the UUID generated by Alias Directory which identifies the Alias
      example: e336c8c8-2945-4be3-af3e-951ec2d01219
    AliasExternalId:
      type: string
      minLength: 1
      maxLength: 36
      description: externalId is a client-provided identifier for the Alias. If clients already use an identifier for the Alias in their internal systems, they can re-use the same value here and manage the Alias with this identifier.
      example: 21267931-7975-4b61-be7a-86915883b2b4
    BaseAliasRequest:
      type: object
      properties:
        profile:
          type: object
          description: |
            Profile details for the customer that owns the Alias.

          properties:
            firstName:
              type: string
              minLength: 1
              maxLength: 35
              example: Alex
              description: First name
            middleName:
              type: string
              minLength: 2
              maxLength: 35
              example: Robert
              description: Middle name
            lastName:
              type: string
              minLength: 1
              maxLength: 35
              example: Miller
              description: Last name
            preferredName:
              type: string
              example: Miller's Shop
              description: Preferred name (or the 'known-as' name)
            dateOfBirth:
              allOf:
                - $ref: "#/components/schemas/Date"
              description: Date of birth.
            contactInfo:
              type: array
              maxItems: 2
              items:
                type: object
                description: Contact information
                properties:
                  value:
                    type: string
                    minLength: 1
                    maxLength: 320
                    example: 1231234321
                    description: Phone number or email address to contact the customer
                  type:
                    type: string
                    example: PHONE
                    enum:
                      - EMAIL
                      - PHONE
                    description: Contact type
                required:
                  - value
                  - type
          required:
            - type
        identification:
          type: object
          description: Form of identification
          properties:
            type:
              type: string
              example: PASSPORT
              description: Identification type, e.g., Driver's License or Passport
              enum:
                - PASSPORT
                - DL
                - NIDN
            value:
              type: string
              minLength: 1
              maxLength: 35
              example: A123456
              description: Identification number for the individual who owns the Alias. The format will change depending the type.
            verificationDetails:
              type: object
              properties:
                creationDateTime:
                  type: string
                  format: date-time
                  description: Time when the user was created or enrolled, in ISO UTC format YYYY-MM-DDThh:mm:ss.000Z
                  example: "2021-01-01T22:52:46.000Z"
                authDateTime:
                  type: string
                  format: date-time
                  example: "2021-01-01T22:52:46.000Z"
                  description: Time when the user provided the credentials for verification, in ISO UTC format YYYY-MM-DDThh:mm:ss.000Z
                authMethodReference:
                  type: string
                  example: EXTERNAL, SMS OTP, Email OTP
                  description: Authentication method used when the user provided the credentials for verification, e.g., SMS OTP
                verifiedEmail:
                  type: boolean
                  example: True
                  description: Boolean value. True if an email was verified during the verification process. This email does not need to match the Alias or contact info if an email was used.
                verifiedPhone:
                  type: boolean
                  example: False
                  description: Boolean value. True if a phone was verified during the verification process. This phone does not need to match the Alias or contact info if a phone was used.
          required:
            - value
            - type
      required:
        - individual
    PaymentCredentialCommon:
      type: object
      required:
        - type
      properties:
        type:
          $ref: "#/components/schemas/PaymentCredentialType"
        preferredFor:
          type: array
          minItems: 0
          maxItems: 3
          description: |
            Indicates if a payment credential is a preferred **Receiving**,  **Sending**, or **Paying** account. **Receiving** allows for funds to be pushed into the payment credential. **Sending** allows for funds to be sent from the payment credential. **Paying** allows for the payment credential to be used for purchases. More than one preferred type can be used.
          items:
            type: object
            properties:
              type:
                type: string
                enum:
                  - RECEIVE
                  - SEND
                  - PAY
              date:
                $ref: '#/components/schemas/DateTime'
            required:
              - type
              - date
        externalId:
          $ref: "#/components/schemas/PaymentCredentialExternalId"
    PaymentCredentialAuditTrail:
      type: object
      required:
        - type
      properties:
        status:
          type: string
          enum:
            - ACTIVE
            - SUSPENDED
          example: ACTIVE
          description: Payment Credential status. DELETED payment credentials won't be returned.
        createdOn:
          allOf:
            - $ref: '#/components/schemas/PaymentCredentialModifiedOn'
          description: Date when it was initially created
          example: "2021-06-21T13:00:00.645Z"
        lastUpdatedOn:
          allOf:
            - $ref: '#/components/schemas/PaymentCredentialModifiedOn'
          description: Date when it was last modified
          example: "2021-06-22T15:12:00.322Z"
    PaymentCredentialId:
      allOf:
        - $ref: "#/components/schemas/Id"
      description: This is an UUID generated by Alias Directory which identifies the payment credential
      example: a44c9553-e687-4f3c-b7e7-a4245d9f238e
    PaymentCredentialExternalId:
      type: string
      minLength: 1
      maxLength: 36
      example: 63421837-d597-4f0f-89e4-930c1a7b9e85
      description: externalId is a client-provided identifier for the payment credential. If clients already use an identifier for the payment credential in their internal systems, they can re-use the same value here and manage the payment credential with this identifier.
    PaymentCredentialItem:
      type: object
      anyOf:
        - $ref: "#/components/schemas/CardDetails"
        - $ref: "#/components/schemas/BankAccountDetails"
    PaymentCredentialCreateUpdateResponse:
      type: object
      properties:
        type:
          $ref: "#/components/schemas/PaymentCredentialType"
        id:
          $ref: "#/components/schemas/PaymentCredentialId"
        externalId:
          $ref: "#/components/schemas/PaymentCredentialExternalId"
      required:
        - type
        - id
    PaymentCredentialItemGetResponse:
      type: object
      anyOf:
        - $ref: "#/components/schemas/CardGetResponse"
        - $ref: "#/components/schemas/BankGetResponse"
    PaymentCredentialItemAliasResponse:
      type: object
      anyOf:
        - $ref: "#/components/schemas/CardAliasResponse"
        - $ref: "#/components/schemas/BankAliasResponse"
    PaymentCredentialsList:
      type: array
      items:
        $ref: '#/components/schemas/PaymentCredentialItemGetResponse'
    PaymentCredentialModifiedOn:
      allOf:
        - $ref: "#/components/schemas/DateTime"
      description: Date of the last update of the payment credential, in ISO UTC format YYYY-MM-DDThh:mm:ss.000Z
      example: "2021-01-01T22:52:46.000Z"
    PaymentCredentialOnePreferredOn:
      type: object
      properties:
        modifiedOn:
          $ref: '#/components/schemas/PaymentCredentialModifiedOn'
        preferredOn:
          allOf:
            - $ref: '#/components/schemas/PaymentCredentialModifiedOn'
          description: Date when it was set as a **preferred** credential
          example: "2021-06-21T13:00:00.323Z"
    PaymentCredentialType:
      type: string
      enum:
        - BANK
        - CARD
      description: Payment credential type
      example: BANK
    DateTime:
      type: string
      format: date-time
      description: ISO formatted Timestamp
      pattern: \d\d\d\d-\d\d-\d\dT\d\d:\d\d:\d\d\.000Z
      example: "2021-01-01T22:52:46.000Z"
    Wallet:
      type: object
      required:
        - accountIdentifier
        - accountIdentifierType
        - countryCode
        - currencyCode
      properties:
        type:
          description: |
            In this case, value must be: `WALLET`
          example: WALLET
        operatorName:
          type: string
          description: This identifies the non-bank wallet operator that has issued the
            account to the recipient and is going to receive and post the funds
            to the recipient's account.
          maxLength: 50
          example: WALLETX
        accountIdentifierType:
          type: string
          enum:
            - PHONENUMBER
            - EMAIL
            - USERNAME
          description: This identifies type of account Identifier. Supported values are
            PHONENUMBER, EMAIL, USERNAME
          example: PHONENUMBER
        accountIdentifier:
          type: string
          description: This identifies the identifier of the recipient’s account with the
            wallet operator to which the funds have to be deposited.  (e.g.
            wallet address - phone/email/custom identifier that identifies the
            account with the digital wallet operator)
          minLength: 1
          maxLength: 50
          example: 85291112222
        countryCode:
          allOf:
            - $ref: "#/components/schemas/CountryCode"
          description: when present, this field contains ISO3166-1 country code of
            recipient’s wallet
          example: "USA"
        currencyCode:
          allOf:
            - $ref: "#/components/schemas/CurrencyCode"
          description: when present, this field contains ISO4217 currency code of
            recipient’s wallet.
          example: "USD"
    WalletAliasResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/Wallet"
    WalletGetResponse:
      type: object
      allOf:
        - $ref: "#/components/schemas/WalletDetails"
        - $ref: "#/components/schemas/PaymentCredentialAuditTrail"
      properties:
        id:
          $ref: '#/components/schemas/PaymentCredentialId'
    WalletDetails:
      type: object
      allOf:
        - $ref: '#/components/schemas/PaymentCredentialCommon'
        - $ref: "#/components/schemas/Wallet"
      properties:
        type:
          description: In this case, value must be `WALLET`
          example: "WALLET"
    AliasInquirySummary:
      type: object
      description: Summary of found Aliases
      properties:
        aliasesTotal:
          description: Number of aliases in request
          type: integer
        aliasesRepeated:
          description: Number of aliases repeated
          type: integer
        aliasesFound:
          description: Number of aliases found
          type: integer
        aliasesNotFound:
          description: Number of aliases not found
          type: integer
      required:
        - aliasesTotal
        - aliasesRepeated
        - aliasesFound
        - aliasesNotFound
    AliasInquiryItem:
      type: object
      properties:
        aliasValue:
          description: |
            This attribute contains the alias data, e.g. phone number, email
            address, etc. <br>If phone number is used for alias, this should be
            provided in accordance with ITU-T E.164 (2010) number structure.
          type: string
        aliasType:
          type: string
          example: PHONE
          description: The type of Alias in aliasValue
          enum:
            - PHONE
            - EMAIL
      required:
        - aliasValue
        - aliasType
    AliasInquiryDetails:
      type: array
      description: Alias information associated with found records
      items:
        $ref: '#/components/schemas/AliasInquiryItem'
    AliasInquiryResponseInternal:
      description: Alias Inquiry Response Payload
      type: object
      properties:
        summary:
          $ref: '#/components/schemas/AliasInquirySummary'
        details:
          $ref: '#/components/schemas/AliasInquiryDetails'
      required:
        - summary  
    AliasInquiryRequest:
      type: object
      properties:
        aliases:
          type: array
          description: Array of Aliases e.g., phone numbers or email addresses.
          items:
            type: string
            description: |
              Aliases that needs to be inquired about. This attribute contains the alias data, e.g. phone number, email
              address, etc. <br>If phone number is used for alias, this should be
              provided in accordance with ITU-T E.164 (2010) number structure.
      required:
        - aliases
  parameters:
    paymentCredentialId:
      in: path
      required: true
      name: paymentCredentialId
      schema:
        $ref: '#/components/schemas/PaymentCredentialId'
    aliasId:
      in: path
      required: true
      name: aliasId
      schema:
        $ref: '#/components/schemas/AliasId'
