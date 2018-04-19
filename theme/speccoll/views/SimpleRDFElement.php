<?php
/**
 * @version		$Id: simplerdfelement.php 3:43 PM 6/30/2011 gregstevens $
 * @copyright		Copyright (C) 2011 Greg Stevens Holdings, LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later
 *
 *
 * NOTE: This class is extremely simple, with only basic functionality, and cannot be
 * guaranteed to work on all well-formed, valid RDF documents. However, I am happy to
 * enrich it and fix it, so feel free to email me with ideas, suggestions, complaints,
 * and so on.
 *
 * The way to create one of these objects from a string variable that contains RDF text:
 *
 *    $xmlobj = simplexml_load_string($xml,'SimpleRDFElement');
 *
 *
 * - Greg Stevens
 *   greg@talkingowlproject.com
 *   http://talkingowlproject.com/
 *
 *   See Also:
 *   http://talkingowlproject.blogspot.com/2011/06/simplerdfelement-class-extension-of.html
 *
 */

/**
 * Class for an RDF Triple
 *
 * This is just a "helper class" to define that simplest possible Triple object.
 * This will hold the output of the getTriples() method  of the Simple RDF Element class.
 *
 */
class SimpleRDFTriple
{
    public $tripleSubject;
    public $triplePredicate;
    public $tripleObject;
}



/**
 * Class for simple RDF element
 *
 * This class extends the SimpleXMLElement to include better namespace handling
 * and methods to extract information in the form of triples.
 *
 */

class SimpleRDFElement extends SimpleXMLElement
{

    /**
     * Return the namespace prefix string associated with this element
     *
     */
    function getPrefix()
    {

        $xml = trim($this->asXML());

        if (strpos($xml,'<')===0)
            $xml = substr($xml,1);

        if (strpos($xml,'?')===0)
            $xml = substr($xml, strpos($xml,'<')+1);

        if (strpos($xml,'>') > 0)
            $xml = substr($xml, 0, strpos($xml,'>'));

        $length = strpos($xml,':');

        if ($length==false)
            $length = 0;

        return substr($xml,0,$length);

    }

    /**
     * Return the full namespace URI for this node.
     * Just a shortcut that makes use of the method above.
     * Cannot be used to set the namespace.
     *
     */
    function getNamespace()
    {
        $nsarray = $this->getNamespaces();

        return $nsarray[ $this->getPrefix() ];
    }

    /**
     * Returns all of the child elements of the current element
     * Unlike children() it will return all children regardless of namespace.
     *
     */
    function getChildNodes()
    {
        $kids = $this->xpath('*');

        return $kids;
    }

    /**
     * Returns all of the attributes of the current element
     * Unlike attributes() it will return all attributesregardless of namespace.
     *
     */
    function getAttributes()
    {
        $atts = $this->xpath('@*');

        return $atts;
    }


    /**
     * Returns the name of the node including the prefixed namespace.
     *
     */
    function getFullName()
    {
        return $this->getPrefix().':'.$this->getName();
    }


    /**
     * Returns the URI of the node, which is the full URI of the namespace
     * with the name of the node appended on to the end.
     *
     */
    function getFullURI()
    {
        return $this->getNamespace().$this->getName();
    }



    /**
     * Returns all of the Triples about the current node subject
     * In RDF, a statement is a triple: (Subject, Predicate, Object)
     * Each of these three items is either a URI or a plain text value
     * Assumptions:
     *  All of the childnodes of a particular element are about the same subject
     *  The uri of the subject is defined by the value of any of these attributes:
     *	rdf:about, rdf:ID, rdf:resource.
     *  If the uri of the root is anything but rdf:Description, then the
     * 	first triple is: {subject URI, rdfs:Class, URI of the root element}
     *  All attributes other than those listed above can be used to form triples
     *  	with the form: {subject URI, attribute URI, attribute value}
     *  All child nodes can be used to form triples
     * 	with the form: {subject URI, child node URI,
     * 		text content or value of rdf:resource attribute}
     *
     *  This does not currently support reification XML (i.e. the rdf:Statement tag).
     *  This might be added later.
     */
    function getTriples()
    {
        $result = array();

        $attlist = $this->attributes('rdf',true);

        $subjectURI = $attlist->about;
        if (!$subjectURI) $subjectURI = $attlist->id;
        if (!$subjectURI) $subjectURI = $attlist->resource;


        if ($this->getFullName() != 'rdf:Description')
        {
            $t = new SimpleRDFTriple();
            $t->tripleSubject = (string)$subjectURI;
            $t->triplePredicate = 'http://www.w3.org/2000/01/rdf-schema#Class';
            $t->tripleObject = (string)$this->getFullURI();
            array_push( $result, $t );
        }

        $kidslist = $this->getAttributes();

        foreach ($kidslist as $kid)
        {
            $a = (string)$kid->getFullName();
            $w = 'xml:lang';
            $x = 'rdf:id';
            $y = 'rdf:about';
            $z = 'rdf:resource';

            if ( ($a!=$w) && ($a!=$x) && ($a!=$y) && ($a!=$z) )
            {
                $t = new SimpleRDFTriple();
                $t->tripleSubject = (string)$subjectURI;
                $t->triplePredicate = (string)$kid->getFullURI();
                $t->tripleObject = (string)$kid;
                array_push( $result, $t );
            }
        }


        $kidslist = $this->getChildNodes();

        foreach ($kidslist as $kid)
        {
            $t = new SimpleRDFTriple();
            $t->tripleSubject = (string)$subjectURI;
            $t->triplePredicate = (string)$kid->getFullURI();
            $kidatts = $kid->attributes('rdf',true);
            $kiduri = $kidatts->about;
            if (!$kiduri) $kiduri = $kidatts->id;
            if (!$kiduri) $kiduri = $kidatts->resource;
            if (!$kiduri) $kiduri = (string)$kid;
            $t->tripleObject = (string)$kiduri;
            array_push( $result, $t );
        }

        return $result;
    }


}
