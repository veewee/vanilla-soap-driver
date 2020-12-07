# vanilla-soap-driver

> ## WIP / POC : Don't use this please ;)


![Here be dragons](https://media.giphy.com/media/Zb0asRm15HqCbgShD4/giphy.gif)


The goal of this package is to provide a vanilla soap driver for phpro/soap-client, so that you don't have to use ext-soap anymore!
Even though ext-soap is already doing a "good enough" job in fetching metadata / encoding / decoding, this results in better XSD type and namespace converage.
Allowing you to generate even better soap client PHP API's:

More info:
 - https://github.com/phpro/soap-client
 - https://github.com/phpro/soap-client/blob/master/docs/drivers/new.md
 
 
 Where we at?
 
 Parsing metadata:
 
 - [X] Basic wsdl and xsd parsing
 - [X] Fetching namespace information
 - [ ] Import xsd / wsdl imports into 1 WSDL
 - [ ] Advanced info : enums / minOccurences / maxOccurences
 - [ ] Change phpro/soap-client metadata types in order to get easier access to advanced info
 - [ ] Add additional info to the phpro/soap-client code generation types
 - [ ] E2E Test suite
 
Encodation

 - [ ] Add vanilla encoder
 - [ ] Add vanilla decoder
 - [ ] Cached metadata so that we don't have to parse the wsdl on runtime
 - [ ] Multi-namespace support
 - [ ] E2E Test suite


...


Loads of work to do :)
