<?php
header("Content-type: text/xml; charset='utf-8'");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

$server = "http://".$_SERVER['HTTP_HOST']."/alkserver/index.php";

?>
<definitions 	xmlns="http://schemas.xmlsoap.org/wsdl/"
				xmlns:soap12bind="http://schemas.xmlsoap.org/wsdl/soap12/"
				xmlns:soapbind="http://schemas.xmlsoap.org/wsdl/soap/"
				
				xmlns:wsp="http://schemas.xmlsoap.org/ws/2004/09/policy" 
	            xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"
	
				xmlns:tns="http://rus:8080/rus/"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	            xmlns:xsd1="http://www.rusexpeditor-response.org"
	            xmlns:xsd2="http://www.rusexpeditor-sendcontractors.org"
	            xmlns:xsd3="http://www.rusexpeditor-senddeal.org"
				name="AliLogisticService"
				targetNamespace="http://rus:8080/rus/">
	<types>
    	<xs:schema 
        		xmlns:tns="http://www.rusexpeditor-response.org" 
        		xmlns:xs="http://www.w3.org/2001/XMLSchema" 
        		targetNamespace="http://www.rusexpeditor-response.org" 
        		attributeFormDefault="unqualified" 
        		elementFormDefault="qualified">
    	        
    		    
        		<xs:complexType name="response">
        			<xs:sequence>
        				<xs:element name="success" type="xs:boolean"/>
        				<xs:element name="error" type="xs:string" nillable="true"/>
        				<xs:element name="errorMessages" type="xs:string" minOccurs="0" maxOccurs="unbounded"/>
        			</xs:sequence>
        		</xs:complexType>
        </xs:schema>
    	
    	<xs:schema 
        		xmlns:tns="http://www.rusexpeditor-sendcontractors.org" 
        		xmlns:xs="http://www.w3.org/2001/XMLSchema" 
        		targetNamespace="http://www.rusexpeditor-sendcontractors.org" 
        		attributeFormDefault="unqualified" 
        		elementFormDefault="qualified">
    
    		<xs:complexType name="customer">
    			<xs:sequence>
    				<xs:element name="uuid" type="xs:string"/>
    				<xs:element name="inn"  type="xs:string"/>
    				<xs:element name="kpp"  type="xs:string" nillable="true"/>
    				<xs:element name="name" type="xs:string"/>
    				<xs:element name="type" type="xs:string"/>
    				<xs:element name="address" type="xs:string"/>
    				<xs:element name="ogrn" type="xs:string" nillable="true"/>
    				<xs:element name="bik" type="xs:string" nillable="true"/>
    				<xs:element name="namebank" type="xs:string" nillable="true"/>
    				<xs:element name="bankaccount" type="xs:string" nillable="true"/>
    				<xs:element name="corraccount" type="xs:string" nillable="true"/>
    				<xs:element name="namecontact" type="xs:string" nillable="true"/>
    				<xs:element name="email" type="xs:string" nillable="true"/>
    				<xs:element name="numberphone" type="xs:string" nillable="true"/>
    			</xs:sequence>
    		</xs:complexType>
    	
    		<xs:complexType name="customers">
    			<xs:sequence>
    				<xs:element name="customers" type="tns:customer" minOccurs="1" maxOccurs="unbounded"/>
    			</xs:sequence>
    		</xs:complexType>
    
    	</xs:schema>
    	
    	<xs:schema 
    		xmlns:tns="http://www.rusexpeditor-senddeal.org" 
    		xmlns:xs="http://www.w3.org/2001/XMLSchema" 
    		targetNamespace="http://www.rusexpeditor-senddeal.org" 
    		attributeFormDefault="unqualified" 
    		elementFormDefault="qualified">
    
    		<xs:complexType name="deal">
    			<xs:sequence>
    				<xs:element name="uuid" type="xs:string"/>
    				<xs:element name="number" type="xs:string"/>
    				<xs:element name="datedoc" type="xs:dateTime"/>
    				<xs:element name="uuidcustomer" type="xs:string"/>
    				<xs:element name="namecargo" type="xs:string"/>
    				<xs:element name="weight" type="xs:float"/>
    				<xs:element name="ts" type="xs:string" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="comments" type="xs:string" nillable="true"/>
    				<xs:element name="nds" type="xs:boolean"/>
    				<xs:element name="countloaders" type="xs:integer" nillable="true"/>
    				<xs:element name="quantityofhours" type="xs:integer" nillable="true"/>
    				<xs:element name="insurance" type="xs:boolean" nillable="true"/>
    				<xs:element name="sum" type="xs:float" nillable="true"/>
    				<xs:element name="temperaturefrom" type="xs:float" nillable="true"/>
    				<xs:element name="temperatureto" type="xs:float" nillable="true"/>
    				<xs:element name="additionalequipment" type="xs:string" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="specialequipment" type="xs:string" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="methodoftransportation" type="xs:string" nillable="true"/>
    				<xs:element name="escort" type="xs:boolean" nillable="true"/>
    				<xs:element name="documentation" type="xs:string" nillable="true" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="size" type="xs:float" nillable="true"/>
    				<xs:element name="length" type="xs:float" nillable="true"/>
    				<xs:element name="width" type="xs:float" nillable="true"/>
    				<xs:element name="height" type="xs:float" nillable="true"/>
    				<xs:element name="methodofloading" type="xs:string" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="methodofunloading" type="xs:string" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="routes" type="tns:route" minOccurs="2" maxOccurs="unbounded"/>
    				<xs:element name="costs" type="tns:cost" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="driver" type="xs:string" nillable="true"/>
    				<xs:element name="vehicle" type="xs:string" nillable="true"/>
    				<xs:element name="status" type="xs:string"/>
    				<xs:element name="printForm" type="xs:base64Binary" nillable="true"/>
    				<xs:element name="howpacked" type="xs:string" nillable="true" minOccurs="0" maxOccurs="unbounded"/>
    				<xs:element name="countplace" type="xs:integer" nillable="true"/>
    				<xs:element name="adrclass" type="xs:integer" nillable="true"/>
    				<xs:element name="reqrussiandriver" type="xs:boolean" nillable="true"/>
    				<xs:element name="crossdocking" type="xs:boolean" nillable="true"/>
    				<xs:element name="securestorage" type="xs:boolean" nillable="true"/>
    				<xs:element name="cargohandling" type="xs:boolean" nillable="true"/>
    			</xs:sequence>
    		</xs:complexType>
    
    		<xs:complexType name="route">
    			<xs:sequence>
    				<xs:element name="uuid" type="xs:string" nillable="true"/>
    				<xs:element name="typeshipment" type="xs:boolean"/>
    				<xs:element name="datefrom" type="xs:dateTime"/>
    				<xs:element name="dateby" type="xs:dateTime"/>
    				<xs:element name="town" type="xs:string" nillable="true"/>
    				<xs:element name="location" type="xs:string"/>
    				<xs:element name="shipper" type="xs:string"/>
    				<xs:element name="contactname" type="xs:string"/>
    				<xs:element name="numberphone" type="xs:string"/>
    				<xs:element name="comment" type="xs:string" nillable="true"/>
    			</xs:sequence>
    		</xs:complexType>
    		
    		<xs:complexType name="cost">
    			<xs:sequence>
    				<xs:element name="uuid" type="xs:string" nillable="true"/>
    				<xs:element name="servicetype" type="xs:string"/>
    				<xs:element name="cost" type="xs:float"/>
    				<xs:element name="quantity" type="xs:integer"/>
    				<xs:element name="sum" type="xs:float"/>
    			</xs:sequence>
    		</xs:complexType>
    	</xs:schema>
		

		<xs:schema 
        		xmlns:tns="http://www.rusexpeditor-sendfile.org" 
        		xmlns:xs="http://www.w3.org/2001/XMLSchema" 
        		targetNamespace="http://www.rusexpeditor-sendfile.org" 
        		attributeFormDefault="unqualified" 
        		elementFormDefault="qualified">
    	        
    		    
        	<xs:complexType name="fileRequest">
        		<xs:sequence>
        			<xs:element name="dealUuid" type="xs:string"/>
        			<xs:element name="fileNumber" type="xs:string"/>
        			<xs:element name="fileDate" type="xs:date"/>
        			<xs:element name="paidDate" type="xs:datetime" nillable="true"/>
        			<xs:element name="sum" type="xs:float" nillable="true"/>
        			<xs:element name="binaryFile" type="xs:base64Binary"/>
        		</xs:sequence>
        	</xs:complexType>
        </xs:schema>


		<xs:schema 	xmlns:tns="http://schemas.xmlsoap.org/wsdl/"
                    xmlns:xs="http://www.w3.org/2001/XMLSchema"
		            xmlns:xs1="http://www.rusexpeditor-response.org"
		            xmlns:xs2="http://www.rusexpeditor-sendcontractors.org"
		            xmlns:xs3="http://www.rusexpeditor-senddeal.org"
		            xmlns:xs4="http://www.rusexpeditor-sendfile.org"
					targetNamespace="http://rus:8080/rus/"
					attributeFormDefault="unqualified"
					elementFormDefault="qualified">
        
		    <xs:import namespace="http://www.rusexpeditor-response.org"/>
		    <xs:import namespace="http://www.rusexpeditor-sendcontractors.org"/>
		    <xs:import namespace="http://www.rusexpeditor-senddeal.org"/>
		    <xs:import namespace="http://www.rusexpeditor-sendfile.org"/>

    		<xs:element name="sendContractorsRequest" type="xs2:customers"/>
    		
    		<xs:element name="sendContractorsResponse" type="xs1:response"/>
    		
			<xs:element name="sendDealRequest" type="xs3:deal"/>
			
    		<xs:element name="sendDealResponse" type="xs1:response"/>
    		
    		<xs:element name="sendFileBillRequest" type="xs4:fileRequest"/>
    		<xs:element name="sendFileBillResponse" type="xs1:response"/>

    		<xs:element name="sendFileActRequest" type="xs4:fileRequest"/>
    		<xs:element name="sendFileActResponse" type="xs1:response"/>
    		
    		<xs:element name="sendFileInvoiceRequest" type="xs4:fileRequest"/>
    		<xs:element name="sendFileInvoiceResponse" type="xs1:response"/>
    		
    		<xs:element name="sendFileContractRequest" type="xs4:fileRequest"/>
    		<xs:element name="sendFileContractResponse" type="xs1:response"/>
    		
    		<xs:element name="sendFilePrintFormRequest" type="xs4:fileRequest"/>
    		<xs:element name="sendFilePrintFormResponse" type="xs1:response"/>
			
			<xs:element name="sendFileDriverRequest" type="xs4:fileRequest"/>
    		<xs:element name="sendFileDriverResponse" type="xs1:response"/>
    		
    		<xs:element name="sendFileTTHRequest" type="xs4:fileRequest"/>
    		<xs:element name="sendFileTTHResponse" type="xs1:response"/>
		</xs:schema>
	</types>

	<message name="sendContractorsRequestMsg">
		<part name="parameters" element="tns:sendContractorsRequest"/>
	</message>
	<message name="sendContractorsResponseMsg">
		<part name="parameters" element="tns:sendContractorsResponse"/>
	</message>
	
	<message name="sendDealRequestMsg">
		<part name="parameters" element="tns:sendDealRequest"/>
	</message>
	<message name="sendDealResponseMsg">
		<part name="parameters" element="tns:sendDealResponse"/>
	</message>
	
	<message name="sendFileBillRequestMsg">
    	<part name="parameters" element="tns:sendFileBillRequest"/>
    </message>
    <message name="sendFileBillResponseMsg">
    	<part name="parameters" element="tns:sendFileBillResponse"/>
    </message>

    <message name="sendFileActRequestMsg">
    	<part name="parameters" element="tns:sendFileActRequest"/>
    </message>
    <message name="sendFileActResponseMsg">
    	<part name="parameters" element="tns:sendFileActResponse"/>
    </message>

    <message name="sendFileInvoiceRequestMsg">
    	<part name="parameters" element="tns:sendFileInvoiceRequest"/>
    </message>
    <message name="sendFileInvoiceResponseMsg">
    	<part name="parameters" element="tns:sendFileInvoiceResponse"/>
    </message>

    <message name="sendFileContractRequestMsg">
    	<part name="parameters" element="tns:sendFileContractRequest"/>
    </message>
    <message name="sendFileContractResponseMsg">
    	<part name="parameters" element="tns:sendFileContractResponse"/>
    </message>

    <message name="sendFileDriverRequestMsg">
    	<part name="parameters" element="tns:sendFileDriverRequest"/>
    </message>
    <message name="sendFileDriverResponseMsg">
    	<part name="parameters" element="tns:sendFileDriverResponse"/>
    </message>

    <message name="sendFilePrintFormRequestMsg">
    	<part name="parameters" element="tns:sendFilePrintFormRequest"/>
    </message>
    <message name="sendFilePrintFormResponseMsg">
    	<part name="parameters" element="tns:sendFilePrintFormResponse"/>
    </message>

    <message name="sendFileTTHRequestMsg">
    	<part name="parameters" element="tns:sendFileTTHRequest"/>
    </message>
    <message name="sendFileTTHResponseMsg">
    	<part name="parameters" element="tns:sendFileTTHResponse"/>
    </message>

	<portType name="rusexeditorPortType">
		<operation name="sendContractors">
			<input message="tns:sendContractorsRequestMsg"/>
			<output message="tns:sendContractorsResponseMsg"/>
		</operation>
		
		<operation name="sendDeal">
			<input message="tns:sendDealRequestMsg"/>
			<output message="tns:sendDealResponseMsg"/>
		</operation>
		
		<operation name="sendFileBill">
			<input message="tns:sendFileBillRequestMsg"/>
			<output message="tns:sendFileBillResponseMsg"/>
		</operation>

		<operation name="sendFileAct">
			<input message="tns:sendFileActRequestMsg"/>
			<output message="tns:sendFileActResponseMsg"/>
		</operation>

		<operation name="sendFileInvoice">
			<input message="tns:sendFileInvoiceRequestMsg"/>
			<output message="tns:sendFileInvoiceResponseMsg"/>
		</operation>

		<operation name="sendFileContract">
			<input message="tns:sendFileContractRequestMsg"/>
			<output message="tns:sendFileContractResponseMsg"/>
		</operation>

		<operation name="sendFileDriverAttorney">
			<input message="tns:sendFileDriverRequestMsg"/>
			<output message="tns:sendFileDriverResponseMsg"/>
		</operation>
		
		<operation name="sendFilePrintForm">
			<input message="tns:sendFilePrintFormRequestMsg"/>
			<output message="tns:sendFilePrintFormResponseMsg"/>
		</operation>
		<operation name="sendFileTTH">
			<input message="tns:sendFileTTHRequestMsg"/>
			<output message="tns:sendFileTTHResponseMsg"/>
		</operation>
	</portType>
	
	<binding name="rusexeditorSoapBinding" type="tns:rusexeditorPortType">
		<soapbind:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
				
		<operation name="sendContractors">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>
		
		<operation name="sendDeal">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>
		
		<operation name="sendFileBill">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>
		
		<operation name="sendFileAct">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileInvoice">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileContract">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileDriverAttorney">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFilePrintForm">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileTTH">
			<soapbind:operation style="document" soapAction=""/>
			<input>
				<soapbind:body use="literal"/>
			</input>
			<output>
				<soapbind:body use="literal"/>
			</output>
		</operation>
	</binding>
	
	<binding name="rusexeditorSoap12Binding"
			type="tns:rusexeditorPortType">
		<soap12bind:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
		
		<operation name="sendContractors">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>
		
		<operation name="sendDeal">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>
		
		<operation name="sendFileBill">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileAct">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileInvoice">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileContract">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileDriverAttorney">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFilePrintForm">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>

		<operation name="sendFileTTH">
			<soap12bind:operation style="document" soapAction=""/>
			<input>
				<soap12bind:body use="literal"/>
			</input>
			<output>
				<soap12bind:body use="literal"/>
			</output>
		</operation>
	</binding>
	
	<service name="rusexeditor">
		<port name="rusexeditorSoap" binding="tns:rusexeditorSoapBinding">
		    <documentation>
				<wsi:Claim xmlns:wsi="http://ws-i.org/schemas/conformanceClaim/" conformsTo="http://ws-i.org/profiles/basic/1.1"/>
			</documentation>
			<soapbind:address location="<?php echo $server;?>"/>
		</port>
		<port name="rusexeditorSoap12" binding="tns:rusexeditorSoap12Binding">
			<soap12bind:address location="<?php echo $server;?>"/>
		</port>
	</service>
</definitions>